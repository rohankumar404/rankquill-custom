<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicBlogController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of published blog posts.
     */
    public function index(Request $request): Response
    {
        $blogs = Blog::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Public/Blog/Index', [
            'blogs' => $blogs,
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    /**
     * Display posts belonging to a specific category.
     */
    public function category(string $categorySlug): Response
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $blogs = Blog::with(['category', 'author'])
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Public/Blog/Index', [
            'category' => $category,
            'blogs' => $blogs,
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    /**
     * Display a single blog post.
     */
    public function show(string $categorySlug, string $postSlug)
    {
        $blog = Blog::with(['category', 'author', 'tags', 'seo'])
            ->published()
            ->where('slug', $postSlug)
            ->firstOrFail();

        // Enforce the correct category-based URL structure
        $actualCategorySlug = $blog->category ? $blog->category->slug : 'uncategorized';
        if ($categorySlug !== $actualCategorySlug) {
            return redirect()->route('blog.show', [
                'category' => $actualCategorySlug,
                'slug' => $blog->slug,
            ]);
        }

        // Generate dynamic Table of Contents (TOC)
        $toc = $this->blogService->generateToc($blog->body);

        // Fetch related posts
        $related = $this->blogService->getRelatedPosts($blog, 3);

        return Inertia::render('Public/Blog/Show', [
            'blog' => $blog,
            'toc' => $toc,
            'related' => $related,
        ]);
    }
}
