<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(Request $request): Response
    {
        $pages = Page::latest()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Pages/Index', [
            'pages' => $pages,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Pages/CreateEdit', [
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
            'team_members' => TeamMember::orderBy('order')->get(['id', 'name', 'position']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'status' => 'required|in:draft,published',
            'content' => 'required|array',
            'content.*.type' => 'required|string|in:hero,cta,faqs,testimonials,team,features,content',
            'content.*.data' => 'required|array',

            // SEO Meta Validation
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
        ]);

        // Extra dynamic layout validation helper
        $this->validateDynamicSections($validated['content']);

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null, // Trait boot generates slug if empty
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        if (! empty($validated['seo'])) {
            $page->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'CMS page created successfully.');
    }

    public function edit(Page $page): Response
    {
        $page->load('seo');

        return Inertia::render('Admin/Pages/CreateEdit', [
            'page' => $page,
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
            'team_members' => TeamMember::orderBy('order')->get(['id', 'name', 'position']),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,'.$page->id,
            'status' => 'required|in:draft,published',
            'content' => 'required|array',
            'content.*.type' => 'required|string|in:hero,cta,faqs,testimonials,team,features,content',
            'content.*.data' => 'required|array',

            // SEO Meta Validation
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
        ]);

        // Extra dynamic layout validation helper
        $this->validateDynamicSections($validated['content']);

        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $page->slug,
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        if (isset($validated['seo'])) {
            $page->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'CMS page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'CMS page deleted successfully.');
    }

    /**
     * Validate section structure within the page builder content.
     */
    protected function validateDynamicSections(array $sections): void
    {
        foreach ($sections as $section) {
            $type = $section['type'];
            $data = $section['data'] ?? [];

            switch ($type) {
                case 'hero':
                    if (empty($data['title'])) {
                        abort(422, 'The Hero section requires a title field.');
                    }
                    break;
                case 'cta':
                    if (empty($data['title']) || empty($data['button_text']) || empty($data['button_url'])) {
                        abort(422, 'The CTA section requires title, button text, and button URL.');
                    }
                    break;
                case 'faqs':
                    if (! isset($data['faq_ids']) || ! is_array($data['faq_ids'])) {
                        abort(422, 'The FAQs section requires an array of FAQ IDs.');
                    }
                    break;
                case 'testimonials':
                    if (! isset($data['testimonial_ids']) || ! is_array($data['testimonial_ids'])) {
                        abort(422, 'The Testimonials section requires an array of testimonial IDs.');
                    }
                    break;
                case 'team':
                    if (! isset($data['member_ids']) || ! is_array($data['member_ids'])) {
                        abort(422, 'The Team section requires an array of member IDs.');
                    }
                    break;
                case 'features':
                    if (! isset($data['items']) || ! is_array($data['items'])) {
                        abort(422, 'The Features section requires an items array.');
                    }
                    break;
                case 'content':
                    if (empty($data['body'])) {
                        abort(422, 'The Content section requires a body text field.');
                    }
                    break;
            }
        }
    }
}
