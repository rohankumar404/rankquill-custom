<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NewsletterSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create role and user
        Role::create(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        // Clear settings cache
        app(SettingsService::class)->clearCache();
    }

    public function test_public_user_can_subscribe_to_newsletter(): void
    {
        Http::fake();

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Thank you! You have successfully subscribed to our newsletter.',
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
            'status' => 'active',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'subscriber@example.com')->first();
        $this->assertNotNull($subscriber->token);
        $this->assertNotNull($subscriber->subscribed_at);
        $this->assertNull($subscriber->unsubscribed_at);
    }

    public function test_subscribe_validates_email_input(): void
    {
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_already_subscribed_user_returns_error(): void
    {
        NewsletterSubscriber::create([
            'email' => 'already@example.com',
            'status' => 'active',
        ]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'already@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_unsubscribed_user_can_resubscribe(): void
    {
        Http::fake();

        $subscriber = NewsletterSubscriber::create([
            'email' => 'unsubscribed@example.com',
            'status' => 'unsubscribed',
            'subscribed_at' => now()->subMonths(2),
            'unsubscribed_at' => now()->subMonth(),
        ]);

        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'unsubscribed@example.com',
        ]);

        $response->assertOk();
        $subscriber->refresh();

        $this->assertEquals('active', $subscriber->status);
        $this->assertNull($subscriber->unsubscribed_at);
        $this->assertNotNull($subscriber->subscribed_at);
    }

    public function test_user_can_unsubscribe_with_valid_token(): void
    {
        Http::fake();

        $subscriber = NewsletterSubscriber::create([
            'email' => 'optout@example.com',
            'status' => 'active',
            'subscribed_at' => now()->subDays(5),
        ]);

        $response = $this->get(route('newsletter.unsubscribe', [
            'email' => 'optout@example.com',
            'token' => $subscriber->token,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Newsletter/Unsubscribe')
            ->where('success', true)
            ->where('email', 'optout@example.com')
        );

        $subscriber->refresh();
        $this->assertEquals('unsubscribed', $subscriber->status);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_unsubscribe_fails_with_invalid_token(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'optout@example.com',
            'status' => 'active',
        ]);

        $response = $this->get(route('newsletter.unsubscribe', [
            'email' => 'optout@example.com',
            'token' => 'wrong-token',
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Newsletter/Unsubscribe')
            ->where('success', false)
        );

        $subscriber->refresh();
        $this->assertEquals('active', $subscriber->status);
        $this->assertNull($subscriber->unsubscribed_at);
    }

    public function test_mailchimp_and_brevo_integrations_are_triggered_when_configured(): void
    {
        // 1. Enable integrations in settings
        Setting::create(['key' => 'mailchimp_enabled', 'value' => 'true', 'group' => 'newsletter']);
        Setting::create(['key' => 'mailchimp_api_key', 'value' => 'mockapi-us20', 'group' => 'newsletter']);
        Setting::create(['key' => 'mailchimp_list_id', 'value' => 'mc-list-abc', 'group' => 'newsletter']);

        Setting::create(['key' => 'brevo_enabled', 'value' => 'true', 'group' => 'newsletter']);
        Setting::create(['key' => 'brevo_api_key', 'value' => 'brevo-secret-key-xyz', 'group' => 'newsletter']);
        Setting::create(['key' => 'brevo_list_id', 'value' => '5', 'group' => 'newsletter']);

        app(SettingsService::class)->clearCache();

        // 2. Mock external requests
        Http::fake([
            'https://us20.api.mailchimp.com/*' => Http::response(['id' => '123'], 200),
            'https://api.brevo.com/*' => Http::response(['id' => 'abc'], 200),
        ]);

        // 3. Subscribe
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => 'sync-test@example.com',
        ]);

        $response->assertOk();

        // 4. Verify mock hits
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'us20.api.mailchimp.com') &&
                   $request->method() === 'PUT' &&
                   $request->hasHeader('Authorization') &&
                   str_contains($request->body(), 'sync-test@example.com');
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.brevo.com/v3/contacts') &&
                   $request->method() === 'POST' &&
                   $request->hasHeader('api-key', 'brevo-secret-key-xyz') &&
                   str_contains($request->body(), 'sync-test@example.com');
        });
    }

    public function test_admin_can_view_dashboard_list_and_export_csv(): void
    {
        $this->actingAs($this->adminUser);

        NewsletterSubscriber::create([
            'email' => 'dashboard@example.com',
            'status' => 'active',
            'created_at' => now()->subDays(2),
        ]);

        // Index
        $response = $this->get(route('admin.newsletter.index'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Newsletter')
            ->has('subscribers')
            ->has('stats')
            ->has('recent_trends')
        );

        // Export
        $response = $this->get(route('admin.newsletter.export'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csvContent = $response->streamedContent();
        $this->assertStringContainsString('ID,Email,Status', $csvContent);
        $this->assertStringContainsString('dashboard@example.com', $csvContent);
    }

    public function test_admin_can_delete_and_unsubscribe_subscriber(): void
    {
        $this->actingAs($this->adminUser);

        // Configure mock sync calls
        Setting::create(['key' => 'mailchimp_enabled', 'value' => 'true', 'group' => 'newsletter']);
        Setting::create(['key' => 'mailchimp_api_key', 'value' => 'mock-us20', 'group' => 'newsletter']);
        Setting::create(['key' => 'mailchimp_list_id', 'value' => 'mc-list', 'group' => 'newsletter']);
        app(SettingsService::class)->clearCache();

        Http::fake([
            'https://us20.api.mailchimp.com/*' => Http::response(['status' => 'unsubscribed'], 200),
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => 'todelete@example.com',
            'status' => 'active',
        ]);

        $response = $this->delete(route('admin.newsletter.destroy', $subscriber));
        $response->assertRedirect();

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'id' => $subscriber->id,
        ]);

        // Assert Mailchimp unsubscribe API was hit
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'us20.api.mailchimp.com') &&
                   $request->method() === 'PUT' &&
                   str_contains($request->body(), 'unsubscribed');
        });
    }
}
