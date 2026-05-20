<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class PagePreviewController extends Controller
{
    /**
     * Generate a secure, temporary signed URL for previewing a page.
     */
    public function generate(Request $request, Page $page): JsonResponse
    {
        // Check if user is authenticated and authorized to preview pages
        // (Usually handled via 'web' and 'admin' middleware, but we can verify here as well)

        $previewUrl = URL::temporarySignedRoute(
            'pages.preview',
            now()->addMinutes(60),
            ['page_id' => $page->id]
        );

        return response()->json([
            'success' => true,
            'preview_url' => $previewUrl,
        ]);
    }

    /**
     * Render the preview of a page with fully hydrated relational block items.
     */
    public function show(Request $request): Response
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'This preview link has expired or is invalid.');
        }

        $pageId = $request->input('page_id');
        $page = Page::findOrFail($pageId);

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

        return Inertia::render('Public/PagePreview', [
            'page' => $page,
            'isPreview' => true,
        ]);
    }
}
