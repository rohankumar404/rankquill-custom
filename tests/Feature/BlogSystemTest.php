<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Services\BlogService;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BlogSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Spatie admin role and user
        Role::create(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
    }

    public function test_an_admin_can_manage_categories(): void
    {
        $this->actingAs($this->adminUser);

        // Store
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Marketing Technology',
            'description' => 'MarTech notes',
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Marketing Technology',
            'slug' => 'marketing-technology',
        ]);

        $category = Category::first();

        // Update
        $response = $this->put(route('admin.categories.update', $category), [
            'name' => 'MarTech & Operations',
            'description' => 'Updated desc',
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'MarTech & Operations',
        ]);

        // Destroy
        $response = $this->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertSoftDeleted($category);
    }

    public function test_an_admin_can_manage_tags(): void
    {
        $this->actingAs($this->adminUser);

        // Store
        $response = $this->post(route('admin.tags.store'), [
            'name' => 'SEO Tips',
        ]);
        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'SEO Tips',
            'slug' => 'seo-tips',
        ]);

        $tag = Tag::first();

        // Update
        $response = $this->put(route('admin.tags.update', $tag), [
            'name' => 'SEO Tactics',
        ]);
        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'SEO Tactics',
        ]);

        // Destroy
        $response = $this->delete(route('admin.tags.destroy', $tag));
        $response->assertRedirect(route('admin.tags.index'));
        $this->assertSoftDeleted($tag);
    }

    public function test_an_admin_can_manage_blog_posts_with_tags_focus_keyword_and_seo(): void
    {
        $this->actingAs($this->adminUser);

        $category = Category::create(['name' => 'SEO']);
        $tag1 = Tag::create(['name' => 'Link Building']);
        $tag2 = Tag::create(['name' => 'Backlinks']);

        // Store
        $response = $this->post(route('admin.blogs.store'), [
            'title' => 'Complete Guide to Link Building',
            'body' => '<h2>Introduction</h2><p>Here is some text.</p><h3>Section 1</h3><p>More text details.</p>',
            'excerpt' => 'An expert guide.',
            'status' => 'published',
            'category_id' => $category->id,
            'focus_keyword' => 'link building guide',
            'tag_ids' => [$tag1->id, $tag2->id],
            'seo' => [
                'meta_title' => 'Link Building SEO Title',
                'meta_description' => 'Best link building guide description.',
            ],
        ]);

        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', [
            'title' => 'Complete Guide to Link Building',
            'focus_keyword' => 'link building guide',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        $blog = Blog::with(['tags', 'seo'])->first();
        $this->assertCount(2, $blog->tags);
        $this->assertEquals('Link Building SEO Title', $blog->seo->meta_title);

        // Verify read time auto-calculation
        // 9 words in body. 9/200 rounded up is 1 minute.
        $this->assertEquals(1, $blog->read_time_minutes);

        // Update
        $response = $this->put(route('admin.blogs.update', $blog), [
            'title' => 'Updated Guide to Link Building',
            'body' => '<h2>Introduction to Links</h2><p>'.str_repeat('word ', 300).'</p>', // 301 words total -> 2 mins
            'status' => 'draft',
            'category_id' => $category->id,
            'focus_keyword' => 'updated focus keyword',
            'tag_ids' => [$tag1->id],
            'seo' => [
                'meta_title' => 'Updated SEO Title',
                'meta_description' => 'Updated description.',
            ],
        ]);

        $response->assertRedirect(route('admin.blogs.index'));
        $blog->refresh();
        $this->assertEquals('Updated Guide to Link Building', $blog->title);
        $this->assertEquals('updated focus keyword', $blog->focus_keyword);
        $this->assertCount(1, $blog->tags);
        $this->assertEquals(2, $blog->read_time_minutes); // Verifies auto recalculation
        $this->assertEquals('Updated SEO Title', $blog->seo->meta_title);

        // Destroy
        $response = $this->delete(route('admin.blogs.destroy', $blog));
        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertSoftDeleted($blog);
    }

    public function test_blog_service_generates_correct_toc_and_related_posts(): void
    {
        $category = Category::create(['name' => 'SEO']);
        $tag = Tag::create(['name' => 'SEO']);

        $blog1 = Blog::create([
            'author_id' => $this->adminUser->id,
            'category_id' => $category->id,
            'title' => 'First Post',
            'slug' => 'first-post',
            'body' => '<h2>First H2 heading</h2><p>Content</p><h3>A nested h3 heading</h3><p>More</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);
        $blog1->tags()->attach($tag);

        $blog2 = Blog::create([
            'author_id' => $this->adminUser->id,
            'category_id' => $category->id,
            'title' => 'Second Post',
            'slug' => 'second-post',
            'body' => 'Content',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $blog2->tags()->attach($tag);

        $service = app(BlogService::class);

        // Test TOC Parser
        $toc = $service->generateToc($blog1->body);
        $this->assertCount(2, $toc);
        $this->assertEquals('First H2 heading', $toc[0]['text']);
        $this->assertEquals('first-h2-heading', $toc[0]['slug']);
        $this->assertEquals('h2', $toc[0]['level']);
        $this->assertEquals('h3', $toc[1]['level']);

        // Test Related Posts
        $related = $service->getRelatedPosts($blog1);
        $this->assertCount(1, $related);
        $this->assertEquals($blog2->id, $related[0]->id);
    }

    public function test_public_blog_routes_load_posts_and_handle_redirects(): void
    {
        $category = Category::create(['name' => 'Design Patterns', 'slug' => 'design-patterns']);
        $blog = Blog::create([
            'author_id' => $this->adminUser->id,
            'category_id' => $category->id,
            'title' => 'Repository Pattern in Laravel',
            'slug' => 'repository-pattern-laravel',
            'body' => '<h2>Intro</h2><p>Body copy here</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 1. Visit listing
        $response = $this->get(route('blog.index'));
        $response->assertOk();

        // 2. Visit category list
        $response = $this->get(route('blog.category', 'design-patterns'));
        $response->assertOk();

        // 3. Visit detail page with correct category
        $response = $this->get(route('blog.show', [
            'category' => 'design-patterns',
            'slug' => 'repository-pattern-laravel',
        ]));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Public/Blog/Show')
            ->has('blog')
            ->has('toc')
            ->has('related')
        );

        // 4. Mismatched category redirect validation
        $response = $this->get('/blog/wrong-category-slug/repository-pattern-laravel');
        $response->assertRedirect('/blog/design-patterns/repository-pattern-laravel');
    }

    public function test_rss_feed_endpoint_serves_valid_xml(): void
    {
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);
        Blog::create([
            'author_id' => $this->adminUser->id,
            'category_id' => $category->id,
            'title' => 'RSS Post Title',
            'slug' => 'rss-post-slug',
            'body' => 'Body text',
            'excerpt' => 'RSS excerpt text',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('blog.feed'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8" ?>', $content);
        $this->assertStringContainsString('<rss version="2.0"', $content);
        $this->assertStringContainsString('<title><![CDATA[RSS Post Title]]></title>', $content);
        $this->assertStringContainsString('rss-post-slug', $content);
    }

    public function test_seo_sitemap_uses_category_slug_prefix_for_blogs(): void
    {
        $category = Category::create(['name' => 'SEO', 'slug' => 'seo']);
        Blog::create([
            'author_id' => $this->adminUser->id,
            'category_id' => $category->id,
            'title' => 'Sitemap Post',
            'slug' => 'sitemap-post-slug',
            'body' => 'Body text',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $seoService = app(SeoService::class);
        $sitemap = $seoService->generateSitemap();

        $urls = collect($sitemap->getTags())->map(fn ($tag) => $tag->url)->toArray();

        $matching = collect($urls)->first(fn ($url) => str_contains($url, '/blog/seo/sitemap-post-slug'));
        $this->assertNotNull($matching);
    }
}
