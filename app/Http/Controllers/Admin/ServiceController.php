<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(Request $request): Response
    {
        $services = Service::latest()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Services/Index', [
            'services' => $services,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Services/CreateEdit', [
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'content' => 'nullable|array',
            'pricing' => 'nullable|array',
            'pricing.*.name' => 'required_with:pricing|string|max:255',
            'pricing.*.price' => 'required_with:pricing|numeric',
            'pricing.*.interval' => 'nullable|string|max:50',
            'pricing.*.description' => 'nullable|string|max:500',
            'pricing.*.features' => 'nullable|array',
            'price_starting_at' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',

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

        $service = Service::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'features' => $validated['features'] ?? null,
            'content' => $validated['content'] ?? null,
            'pricing' => $validated['pricing'] ?? null,
            'price_starting_at' => $validated['price_starting_at'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['seo'])) {
            $service->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): Response
    {
        $service->load('seo');

        return Inertia::render('Admin/Services/CreateEdit', [
            'service' => $service,
            'faqs' => Faq::orderBy('order')->get(['id', 'question']),
            'testimonials' => Testimonial::latest()->get(['id', 'client_name', 'client_company']),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,'.$service->id,
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'content' => 'nullable|array',
            'pricing' => 'nullable|array',
            'pricing.*.name' => 'required_with:pricing|string|max:255',
            'pricing.*.price' => 'required_with:pricing|numeric',
            'pricing.*.interval' => 'nullable|string|max:50',
            'pricing.*.description' => 'nullable|string|max:500',
            'pricing.*.features' => 'nullable|array',
            'price_starting_at' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',

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

        $service->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $service->slug,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'features' => $validated['features'] ?? null,
            'content' => $validated['content'] ?? null,
            'pricing' => $validated['pricing'] ?? null,
            'price_starting_at' => $validated['price_starting_at'] ?? null,
            'status' => $validated['status'],
        ]);

        if (isset($validated['seo'])) {
            $service->updateSeo($validated['seo']);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
