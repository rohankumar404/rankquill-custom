<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ServiceModuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Spatie Admin role and User
        Role::create(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
    }

    public function test_an_admin_can_manage_services(): void
    {
        $this->actingAs($this->adminUser);

        // Store
        $response = $this->post(route('admin.services.store'), [
            'title' => 'Machine Learning Integration',
            'tagline' => 'Scale with automated agents',
            'description' => 'Fine-tuning models and embeddings.',
            'price_starting_at' => 2999.00,
            'features' => ['Llama 3 setup', 'Vector search', 'Daily runs'],
            'status' => 'active',
            'pricing' => [
                [
                    'name' => 'Agent Setup',
                    'price' => 2999,
                    'interval' => 'one-time',
                    'description' => 'Set up initial LLM prompt loops',
                    'features' => ['1 model pipeline', 'API gateway integration'],
                ],
            ],
            'content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'title' => 'Dynamic AI Heroes',
                        'subtitle' => 'Unlocking potential',
                    ],
                ],
            ],
            'seo' => [
                'meta_title' => 'ML Integration SEO Title',
                'meta_description' => 'Expert machine learning services page.',
            ],
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', [
            'title' => 'Machine Learning Integration',
            'slug' => 'machine-learning-integration',
            'status' => 'active',
            'price_starting_at' => 2999.00,
        ]);

        $service = Service::first();
        $this->assertEquals('ML Integration SEO Title', $service->seo->meta_title);
        $this->assertCount(1, $service->content);
        $this->assertCount(1, $service->pricing);

        // Update
        $response = $this->put(route('admin.services.update', $service), [
            'title' => 'Updated AI & ML Integration',
            'tagline' => 'New tagline details',
            'description' => 'Updated desc text',
            'price_starting_at' => 3500.00,
            'status' => 'inactive',
            'seo' => [
                'meta_title' => 'Updated ML SEO Title',
                'meta_description' => 'Updated description.',
            ],
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $service->refresh();
        $this->assertEquals('Updated AI & ML Integration', $service->title);
        $this->assertEquals('inactive', $service->status);
        $this->assertEquals(3500.00, $service->price_starting_at);
        $this->assertEquals('Updated ML SEO Title', $service->seo->meta_title);

        // Destroy
        $response = $this->delete(route('admin.services.destroy', $service));
        $response->assertRedirect(route('admin.services.index'));
        $this->assertSoftDeleted($service);
    }

    public function test_public_routes_display_active_services_and_resolve_page_data(): void
    {
        $service = Service::create([
            'title' => 'Mobile App Development',
            'slug' => 'mobile-app-dev',
            'tagline' => 'iOS & Android platforms',
            'description' => 'Develop native mobile applications.',
            'price_starting_at' => 4999.00,
            'status' => 'active',
            'pricing' => [
                [
                    'name' => 'iOS Only Plan',
                    'price' => 4999,
                    'interval' => 'one-time',
                ],
            ],
        ]);

        // Add polymorphic FAQs
        $faq = Faq::create([
            'question' => 'How long does app store submission take?',
            'answer' => 'Typically 2 to 5 days.',
            'faqable_id' => $service->id,
            'faqable_type' => Service::class,
            'order' => 1,
        ]);

        // Add associated Testimonial
        $testimonial = Testimonial::create([
            'client_name' => 'Alex Rivera',
            'client_position' => 'CTO',
            'client_company' => 'ScaleOps',
            'testimonial' => 'RankQuill delivered a flawless application.',
            'rating' => 5,
            'service_id' => $service->id,
        ]);

        // 1. Visit listing page
        $response = $this->get(route('services.index'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Services/Index')
            ->has('services')
        );

        // 2. Visit detail page
        $response = $this->get(route('services.show', 'mobile-app-dev'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Services/Show')
            ->has('service')
        );

        // Test that inactive service throws 404
        $service->update(['status' => 'inactive']);
        $response = $this->get(route('services.show', 'mobile-app-dev'));
        $response->assertStatus(404);
    }

    public function test_active_services_are_included_in_sitemaps(): void
    {
        $service = Service::create([
            'title' => 'SEO Dynamic Service',
            'slug' => 'seo-dynamic-service',
            'status' => 'active',
        ]);

        $seoService = app(SeoService::class);
        $sitemap = $seoService->generateSitemap();

        $urls = collect($sitemap->getTags())->map(fn ($tag) => $tag->url)->toArray();
        $matching = collect($urls)->first(fn ($url) => str_contains($url, '/services/seo-dynamic-service'));
        $this->assertNotNull($matching);

        // Inactive services should be excluded
        $service->update(['status' => 'inactive']);
        $sitemap = $seoService->generateSitemap();
        $urls = collect($sitemap->getTags())->map(fn ($tag) => $tag->url)->toArray();
        $matching = collect($urls)->first(fn ($url) => str_contains($url, '/services/seo-dynamic-service'));
        $this->assertNull($matching);
    }
}
