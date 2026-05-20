<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $blogs = Blog::with(['category', 'author'])
            ->latest()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->input('category_id'), function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => $blogs,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['search', 'category_id']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Blogs/CreateEdit', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string|max:1000',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'focus_keyword' => 'nullable|string|max:255',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',

            // SEO validation
            'seo' => 'nullable|array',
            'seo.meta_title' => 'required_with:seo|string|max:255',
            'seo.meta_description' => 'required_with:seo|string|max:1000',
            'seo.meta_keywords' => 'nullable|string|max:1000',
            'seo.robots' => 'nullable|string|max:255',
            'seo.canonical_url' => 'nullable|url|max:255',
            'seo.og_title' => 'nullable|string|max:255',
            'seo.og_description' => 'nullable|string|max:1000',
            'seo.og_image_url' => 'nullable|url|max:255',
            'seo.twitter_card' => 'nullable|string|max:50',
            'seo.twitter_title' => 'nullable|string|max:255',
            'seo.twitter_description' => 'nullable|string|max:1000',
            'seo.twitter_image' => 'nullable|url|max:255',
            'seo.schema_markup' => 'nullable|array',
        ]);

        $publishedAt = $validated['published_at'] ?? null;
        if ($validated['status'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        $blog = Blog::create([
            'author_id' => auth()->id() ?? 1, // Fallback to 1 for seeds/tests
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'status' => $validated['status'],
            'published_at' => $publishedAt,
            'focus_keyword' => $validated['focus_keyword'] ?? null,
        ]);

        if (! empty($validated['tag_ids'])) {
            $blog->tags()->sync($validated['tag_ids']);
        }

        if (! empty($validated['seo'])) {
            $blog->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog): Response
    {
        $blog->load(['seo', 'tags']);

        return Inertia::render('Admin/Blogs/CreateEdit', [
            'blog' => $blog,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,'.$blog->id,
            'excerpt' => 'nullable|string|max:1000',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'focus_keyword' => 'nullable|string|max:255',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',

            // SEO validation
            'seo' => 'nullable|array',
            'seo.meta_title' => 'required_with:seo|string|max:255',
            'seo.meta_description' => 'required_with:seo|string|max:1000',
            'seo.meta_keywords' => 'nullable|string|max:1000',
            'seo.robots' => 'nullable|string|max:255',
            'seo.canonical_url' => 'nullable|url|max:255',
            'seo.og_title' => 'nullable|string|max:255',
            'seo.og_description' => 'nullable|string|max:1000',
            'seo.og_image_url' => 'nullable|url|max:255',
            'seo.twitter_card' => 'nullable|string|max:50',
            'seo.twitter_title' => 'nullable|string|max:255',
            'seo.twitter_description' => 'nullable|string|max:1000',
            'seo.twitter_image' => 'nullable|url|max:255',
            'seo.schema_markup' => 'nullable|array',
        ]);

        $publishedAt = $validated['published_at'] ?? $blog->published_at;
        if ($validated['status'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        $blog->update([
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $blog->slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'status' => $validated['status'],
            'published_at' => $publishedAt,
            'focus_keyword' => $validated['focus_keyword'] ?? $blog->focus_keyword,
        ]);

        // Sync tags (even if array is empty)
        $blog->tags()->sync($validated['tag_ids'] ?? []);

        if (isset($validated['seo'])) {
            $blog->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
