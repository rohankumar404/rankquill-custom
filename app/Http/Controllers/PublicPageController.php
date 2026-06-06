<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    /**
     * Display a published CMS page by slug.
     */
    public function show(string $slug): Response
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        // Hydrate the dynamic sections referencing other models
        $content = $page->content ?? [];
        $hydratedContent = [];

        foreach ($content as $section) {
            $type = $section['type'] ?? '';
            $data = $section['data'] ?? [];

            if ($type === 'faqs' && ! empty($data['faq_ids'])) {
                $data['resolved_items'] = Faq::whereIn('id', $data['faq_ids'])
                    ->orderBy('order')
                    ->get();
            } elseif ($type === 'testimonials' && ! empty($data['testimonial_ids'])) {
                $data['resolved_items'] = Testimonial::whereIn('id', $data['testimonial_ids'])
                    ->latest()
                    ->get();
            } elseif ($type === 'team' && ! empty($data['member_ids'])) {
                $data['resolved_items'] = TeamMember::whereIn('id', $data['member_ids'])
                    ->orderBy('order')
                    ->get();
            }

            $hydratedContent[] = [
                'type' => $type,
                'data' => $data,
            ];
        }

        // Set hydrated content dynamically in-memory
        $page->setAttribute('content', $hydratedContent);
        $page->load('seo');

        return Inertia::render('Public/PageShow', [
            'page' => $page,
            'isPreview' => false,
        ]);
    }
}
