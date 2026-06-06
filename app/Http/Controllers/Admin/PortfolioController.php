<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(Request $request): Response
    {
        $portfolios = Portfolio::latest()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('project_title', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Portfolios/Index', [
            'portfolios' => $portfolios,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        $categories = Category::all();

        return Inertia::render('Admin/Portfolios/CreateEdit', [
            'categories' => $categories,
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolios,slug',
            'challenge' => 'nullable|string|max:5000',
            'approach' => 'nullable|string|max:5000',
            'results' => 'nullable|string|max:5000',
            'metrics' => 'nullable|array',
            'metrics.*.label' => 'required_with:metrics|string|max:100',
            'metrics.*.value' => 'required_with:metrics|string|max:100',
            'before_image_path' => 'nullable|string|max:500',
            'after_image_path' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string|max:500',
            'video_url' => 'nullable|string|max:500',
            'content' => 'nullable|array',
            'completed_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',

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

        $portfolio = Portfolio::create([
            'client_name' => $validated['client_name'],
            'project_title' => $validated['project_title'],
            'slug' => $validated['slug'] ?? null,
            'challenge' => $validated['challenge'] ?? null,
            'approach' => $validated['approach'] ?? null,
            'results' => $validated['results'] ?? null,
            'metrics' => $validated['metrics'] ?? null,
            'before_image_path' => $validated['before_image_path'] ?? null,
            'after_image_path' => $validated['after_image_path'] ?? null,
            'gallery' => $validated['gallery'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'completed_at' => $validated['completed_at'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['category_ids'])) {
            $portfolio->categories()->sync($validated['category_ids']);
        }

        if (! empty($validated['seo'])) {
            $portfolio->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio): Response
    {
        $portfolio->load(['seo', 'categories']);
        $categories = Category::all();

        return Inertia::render('Admin/Portfolios/CreateEdit', [
            'portfolio' => $portfolio,
            'categories' => $categories,
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
        ]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolios,slug,'.$portfolio->id,
            'challenge' => 'nullable|string|max:5000',
            'approach' => 'nullable|string|max:5000',
            'results' => 'nullable|string|max:5000',
            'metrics' => 'nullable|array',
            'metrics.*.label' => 'required_with:metrics|string|max:100',
            'metrics.*.value' => 'required_with:metrics|string|max:100',
            'before_image_path' => 'nullable|string|max:500',
            'after_image_path' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string|max:500',
            'video_url' => 'nullable|string|max:500',
            'content' => 'nullable|array',
            'completed_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',

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

        $portfolio->update([
            'client_name' => $validated['client_name'],
            'project_title' => $validated['project_title'],
            'slug' => $validated['slug'] ?? $portfolio->slug,
            'challenge' => $validated['challenge'] ?? null,
            'approach' => $validated['approach'] ?? null,
            'results' => $validated['results'] ?? null,
            'metrics' => $validated['metrics'] ?? null,
            'before_image_path' => $validated['before_image_path'] ?? null,
            'after_image_path' => $validated['after_image_path'] ?? null,
            'gallery' => $validated['gallery'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'completed_at' => $validated['completed_at'] ?? null,
            'status' => $validated['status'],
        ]);

        $portfolio->categories()->sync($validated['category_ids'] ?? []);

        if (isset($validated['seo'])) {
            $portfolio->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio deleted successfully.');
    }
}
