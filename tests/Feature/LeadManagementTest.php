<?php

namespace Tests\Feature;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LeadManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Spatie Admin role and User
        Role::create(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        // Clean cache
        app(SettingsService::class)->clearCache();
    }

    public function test_public_can_submit_contact_form_and_triggers_notifications(): void
    {
        Mail::fake();

        // Seed notification emails list setting
        Setting::create([
            'key' => 'lead_notification_emails',
            'value' => 'admin1@rankquill.com,admin2@rankquill.com',
            'group' => 'leads',
        ]);
        app(SettingsService::class)->clearCache();

        $response = $this->postJson(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'company' => 'Doe Enterprises',
            'subject' => 'Project Inquiry',
            'message' => 'Hello, I want to build a custom application.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you! Your message has been received.',
        ]);

        $this->assertDatabaseHas('inquiries', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'company' => 'Doe Enterprises',
            'subject' => 'Project Inquiry',
        ]);

        // Verify Mail sent to both emails
        Mail::assertSent(InquiryReceived::class, function ($mail) {
            return $mail->hasTo('admin1@rankquill.com') && $mail->hasTo('admin2@rankquill.com');
        });
    }

    public function test_honeypot_discards_spam_submissions_silently(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Spam Bot',
            'email' => 'bot@spam.com',
            'website' => 'http://malicious.com', // Honeypot filled
            'message' => 'Click this link now!',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you! Your message has been received.',
        ]);

        // Database should be empty
        $this->assertDatabaseMissing('inquiries', [
            'name' => 'Spam Bot',
        ]);

        // No mail sent
        Mail::assertNothingSent();
    }

    public function test_recaptcha_verification_enforced_when_active(): void
    {
        Mail::fake();

        // 1. Enable reCAPTCHA settings
        Setting::create(['key' => 'recaptcha_enabled', 'value' => 'true', 'group' => 'leads']);
        Setting::create(['key' => 'recaptcha_secret_key', 'value' => 'secret-key-123', 'group' => 'leads']);
        app(SettingsService::class)->clearCache();

        // 2. Mock Google API responses sequence (Failed, then Successful)
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::sequence()
                ->push(['success' => false], 200)
                ->push(['success' => true], 200),
        ]);

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'Verification test message.',
            'g-recaptcha-response' => 'invalid-token',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['recaptcha']);
        $this->assertDatabaseMissing('inquiries', ['name' => 'Jane Smith']);

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'Verification test message.',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('inquiries', ['name' => 'Jane Smith']);
    }

    public function test_admin_can_manage_leads_and_export_csv(): void
    {
        $this->actingAs($this->adminUser);

        $inquiry = Inquiry::create([
            'name' => 'Alex Mercer',
            'email' => 'alex@mercer.com',
            'message' => 'Lead lifecycle test.',
            'status' => 'new',
        ]);

        // 1. Index
        $response = $this->get(route('admin.inquiries.index'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Inquiries')
            ->has('inquiries')
        );

        // 2. Update Status
        $response = $this->put(route('admin.inquiries.update', $inquiry), [
            'status' => 'processing',
        ]);
        $response->assertRedirect();
        $inquiry->refresh();
        $this->assertEquals('processing', $inquiry->status);

        // 3. Export CSV
        $response = $this->get(route('admin.inquiries.export'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('ID,Name,Email', $content);
        $this->assertStringContainsString('Alex Mercer', $content);

        // 4. Soft Delete
        $response = $this->delete(route('admin.inquiries.destroy', $inquiry));
        $response->assertRedirect();
        $this->assertSoftDeleted($inquiry);
    }
}
