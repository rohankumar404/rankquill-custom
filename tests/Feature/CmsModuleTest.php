<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CmsModuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up the admin role
        Role::create(['name' => 'admin']);

        // Create an admin user for authentication
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
    }

    public function test_an_admin_can_manage_faqs(): void
    {
        $this->actingAs($this->adminUser);

        // Store FAQ
        $response = $this->post(route('admin.faqs.store'), [
            'question' => 'What is RankQuill?',
            'answer' => 'An agency rank helper.',
            'order' => 1,
        ]);
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', [
            'question' => 'What is RankQuill?',
        ]);

        $faq = Faq::first();

        // Update FAQ
        $response = $this->put(route('admin.faqs.update', $faq), [
            'question' => 'What is RankQuill CMS?',
            'answer' => 'An agency CMS helper.',
            'order' => 2,
        ]);
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'What is RankQuill CMS?',
            'order' => 2,
        ]);

        // Destroy FAQ
        $response = $this->delete(route('admin.faqs.destroy', $faq));
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertSoftDeleted($faq);
    }

    public function test_an_admin_can_manage_testimonials(): void
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $avatar = UploadedFile::fake()->image('john.jpg');

        // Store Testimonial
        $response = $this->post(route('admin.testimonials.store'), [
            'client_name' => 'John Doe',
            'client_position' => 'CEO',
            'client_company' => 'Google',
            'testimonial' => 'Great experience!',
            'rating' => 5,
            'is_featured' => true,
            'avatar' => $avatar,
        ]);
        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'John Doe',
            'is_featured' => true,
        ]);

        $testimonial = Testimonial::first();
        $this->assertNotNull($testimonial->avatar_path);

        // Update Testimonial
        $response = $this->put(route('admin.testimonials.update', $testimonial), [
            'client_name' => 'John A. Doe',
            'client_position' => 'CTO',
            'client_company' => 'Alphabet',
            'testimonial' => 'Awesome experience!',
            'rating' => 4,
            'is_featured' => false,
        ]);
        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'client_name' => 'John A. Doe',
            'client_position' => 'CTO',
            'rating' => 4,
            'is_featured' => false,
        ]);

        // Destroy Testimonial
        $response = $this->delete(route('admin.testimonials.destroy', $testimonial));
        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertSoftDeleted($testimonial);
    }

    public function test_an_admin_can_manage_team_members(): void
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $avatar = UploadedFile::fake()->image('jane.png');

        // Store Team Member
        $response = $this->post(route('admin.team-members.store'), [
            'name' => 'Jane Smith',
            'position' => 'Product Manager',
            'bio' => 'Likes building products.',
            'order' => 1,
            'is_active' => true,
            'avatar' => $avatar,
            'social_links' => [
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/in/jane'],
            ],
        ]);
        $response->assertRedirect(route('admin.team-members.index'));
        $this->assertDatabaseHas('team_members', [
            'name' => 'Jane Smith',
            'position' => 'Product Manager',
        ]);

        $member = TeamMember::first();
        $this->assertCount(1, $member->social_links);
        $this->assertNotNull($member->avatar_path);

        // Update Team Member
        $response = $this->put(route('admin.team-members.update', $member), [
            'name' => 'Jane R. Smith',
            'position' => 'Lead Product Manager',
            'bio' => 'Likes building products.',
            'order' => 2,
            'is_active' => false,
            'social_links' => [
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/in/jane-r'],
            ],
        ]);
        $response->assertRedirect(route('admin.team-members.index'));
        $this->assertDatabaseHas('team_members', [
            'id' => $member->id,
            'name' => 'Jane R. Smith',
            'position' => 'Lead Product Manager',
            'is_active' => false,
        ]);

        // Destroy Team Member
        $response = $this->delete(route('admin.team-members.destroy', $member));
        $response->assertRedirect(route('admin.team-members.index'));
        $this->assertSoftDeleted($member);
    }

    public function test_an_admin_can_manage_pages_with_dynamic_sections_and_seo(): void
    {
        $this->actingAs($this->adminUser);

        // Create references
        $faq = Faq::create(['question' => 'Q', 'answer' => 'A', 'order' => 1]);
        $testimonial = Testimonial::create(['client_name' => 'Client', 'testimonial' => 'Feedback', 'rating' => 5, 'is_featured' => true]);
        $member = TeamMember::create(['name' => 'Member', 'position' => 'Role', 'order' => 1]);

        $content = [
            [
                'type' => 'hero',
                'data' => [
                    'title' => 'RankQuill Hero Title',
                    'subtitle' => 'Hero Subtitle',
                    'image' => 'https://rankquill.com/hero.jpg',
                    'primary_cta_text' => 'Get Started',
                    'primary_cta_url' => '/start',
                ],
            ],
            [
                'type' => 'faqs',
                'data' => [
                    'title' => 'Frequently Asked Questions',
                    'faq_ids' => [$faq->id],
                ],
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'title' => 'What Clients Say',
                    'testimonial_ids' => [$testimonial->id],
                ],
            ],
            [
                'type' => 'team',
                'data' => [
                    'title' => 'Meet Our Experts',
                    'member_ids' => [$member->id],
                ],
            ],
        ];

        // Store Page
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Home Page',
            'status' => 'draft',
            'content' => $content,
            'seo' => [
                'meta_title' => 'RankQuill SEO Home Title',
                'meta_description' => 'Best rank tracker description.',
                'meta_keywords' => 'seo, ranking',
            ],
        ]);
        $response->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseHas('pages', [
            'title' => 'Home Page',
            'status' => 'draft',
        ]);

        $page = Page::with('seo')->first();
        $this->assertNotNull($page->seo);
        $this->assertEquals('RankQuill SEO Home Title', $page->seo->meta_title);

        // Update Page
        $newContent = [
            [
                'type' => 'content',
                'data' => [
                    'body' => 'Dynamic rich body content.',
                ],
            ],
        ];
        $response = $this->put(route('admin.pages.update', $page), [
            'title' => 'Updated Home Page',
            'status' => 'published',
            'content' => $newContent,
            'seo' => [
                'meta_title' => 'Updated SEO Title',
                'meta_description' => 'Updated best description.',
            ],
        ]);
        $response->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Updated Home Page',
            'status' => 'published',
        ]);

        $page->refresh();
        $this->assertEquals('Updated SEO Title', $page->seo->meta_title);
    }

    public function test_page_creation_fails_if_dynamic_layout_section_rules_are_violated(): void
    {
        $this->actingAs($this->adminUser);

        // Invalid hero section missing title
        $invalidContent = [
            [
                'type' => 'hero',
                'data' => [
                    'subtitle' => 'Hero Subtitle',
                ],
            ],
        ];

        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Home Page',
            'status' => 'draft',
            'content' => $invalidContent,
        ]);

        $response->assertStatus(422);
    }

    public function test_a_page_can_be_previewed_securely_using_signed_urls(): void
    {
        $this->actingAs($this->adminUser);

        // Create references
        $faq = Faq::create(['question' => 'Q', 'answer' => 'A', 'order' => 1]);
        $page = Page::create([
            'title' => 'Preview Page',
            'status' => 'draft',
            'content' => [
                [
                    'type' => 'faqs',
                    'data' => [
                        'title' => 'FAQS',
                        'faq_ids' => [$faq->id],
                    ],
                ],
            ],
        ]);

        // Generate signed URL
        $response = $this->post(route('admin.pages.generate-preview', $page));
        $response->assertOk();
        $this->assertTrue($response->json('success'));
        $previewUrl = $response->json('preview_url');
        $this->assertNotEmpty($previewUrl);

        // Access preview URL publically (without admin credentials)
        auth()->logout();

        $previewResponse = $this->get($previewUrl);
        $previewResponse->assertOk();

        // Assert that the page was passed to Inertia, and content is hydrated with Resolved items!
        $inertiaData = $previewResponse->original->getData()['page']['props'];
        $hydratedPage = $inertiaData['page'];
        $this->assertEquals($page->id, $hydratedPage['id']);

        $resolvedFaq = $hydratedPage['content'][0]['data']['resolved_items'][0];
        $this->assertEquals($faq->id, $resolvedFaq['id']);
    }
}
