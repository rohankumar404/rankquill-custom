<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortfolioSystemTest extends TestCase
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

    public function test_an_admin_can_manage_portfolios(): void
    {
        $this->actingAs($this->adminUser);

        $category = Category::create([
            'name' => 'SEO Operations',
            'slug' => 'seo-ops',
        ]);

        // Store
        $response = $this->post(route('admin.portfolios.store'), [
            'client_name' => 'Zenith Tech',
            'project_title' => 'Speed Optimization Project',
            'challenge' => 'Slow pages caused drop-offs.',
            'approach' => 'Vite configuration tuning.',
            'results' => 'Sub-second load times.',
            'metrics' => [
                ['label' => 'Performance Index', 'value' => '99/100'],
            ],
            'before_image_path' => '/images/before-zenith.jpg',
            'after_image_path' => '/images/after-zenith.jpg',
            'gallery' => ['/images/z1.jpg', '/images/z2.jpg'],
            'video_url' => 'https://youtube.com/embed/xyz',
            'completed_at' => '2026-05-15',
            'status' => 'published',
            'category_ids' => [$category->id],
            'seo' => [
                'meta_title' => 'Zenith Case Study SEO Title',
                'meta_description' => 'A masterclass in speed tuning.',
            ],
        ]);

        $response->assertRedirect(route('admin.portfolios.index'));
        $this->assertDatabaseHas('portfolios', [
            'client_name' => 'Zenith Tech',
            'project_title' => 'Speed Optimization Project',
            'status' => 'published',
            'video_url' => 'https://youtube.com/embed/xyz',
        ]);

        $portfolio = Portfolio::first();
        $this->assertEquals('Zenith Case Study SEO Title', $portfolio->seo->meta_title);
        $this->assertCount(1, $portfolio->categories);
        $this->assertCount(1, $portfolio->metrics);
        $this->assertCount(2, $portfolio->gallery);

        // Update
        $response = $this->put(route('admin.portfolios.update', $portfolio), [
            'client_name' => 'Updated Zenith Tech',
            'project_title' => 'Updated Speed Optimization Project',
            'completed_at' => '2026-05-16',
            'status' => 'draft',
            'seo' => [
                'meta_title' => 'Updated SEO Title',
                'meta_description' => 'Updated desc.',
            ],
        ]);

        $response->assertRedirect(route('admin.portfolios.index'));
        $portfolio->refresh();
        $this->assertEquals('Updated Zenith Tech', $portfolio->client_name);
        $this->assertEquals('draft', $portfolio->status);
        $this->assertEquals('Updated SEO Title', $portfolio->seo->meta_title);

        // Destroy
        $response = $this->delete(route('admin.portfolios.destroy', $portfolio));
        $response->assertRedirect(route('admin.portfolios.index'));
        $this->assertSoftDeleted($portfolio);
    }

    public function test_public_routes_display_published_portfolios_and_filters(): void
    {
        $category1 = Category::create(['name' => 'Marketing', 'slug' => 'marketing']);
        $category2 = Category::create(['name' => 'Engineering', 'slug' => 'engineering']);

        $portfolio = Portfolio::create([
            'client_name' => 'Initech Corp',
            'project_title' => 'Automated Testing Setup',
            'slug' => 'automated-testing-setup',
            'challenge' => 'Manual test errors.',
            'status' => 'published',
            'content' => [
                [
                    'type' => 'content',
                    'data' => [
                        'body' => '<p>Dynamic case body</p>',
                    ],
                ],
            ],
        ]);

        $portfolio->categories()->sync([$category2->id]);

        // 1. Visit index and check lists and categories filters
        $response = $this->get(route('portfolio.index'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Portfolio/Index')
            ->has('portfolios')
            ->has('categories')
        );

        // 2. Filter by category
        $response = $this->get(route('portfolio.index', ['category' => 'engineering']));
        $response->assertOk();

        $response = $this->get(route('portfolio.index', ['category' => 'marketing']));
        $response->assertOk();

        // 3. Visit detail page
        $response = $this->get(route('portfolio.show', 'automated-testing-setup'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Portfolio/Show')
            ->has('portfolio')
        );

        // Test draft throws 404
        $portfolio->update(['status' => 'draft']);
        $response = $this->get(route('portfolio.show', 'automated-testing-setup'));
        $response->assertStatus(404);
    }

    public function test_published_portfolios_are_included_in_sitemaps(): void
    {
        $portfolio = Portfolio::create([
            'client_name' => 'Venture Inc',
            'project_title' => 'Sitemap Portfolio Project',
            'slug' => 'sitemap-portfolio-project',
            'status' => 'published',
        ]);

        $seoService = app(SeoService::class);
        $sitemap = $seoService->generateSitemap();

        $urls = collect($sitemap->getTags())->map(fn ($tag) => $tag->url)->toArray();
        $matching = collect($urls)->first(fn ($url) => str_contains($url, '/portfolio/sitemap-portfolio-project'));
        $this->assertNotNull($matching);

        // Drafts should be excluded
        $portfolio->update(['status' => 'draft']);
        $sitemap = $seoService->generateSitemap();
        $urls = collect($sitemap->getTags())->map(fn ($tag) => $tag->url)->toArray();
        $matching = collect($urls)->first(fn ($url) => str_contains($url, '/portfolio/sitemap-portfolio-project'));
        $this->assertNull($matching);
    }
}
