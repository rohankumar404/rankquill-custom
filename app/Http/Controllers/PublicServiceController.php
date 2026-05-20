<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicServiceController extends Controller
{
    /**
     * Display a listing of active services.
     */
    public function index(Request $request): Response
    {
        $services = Service::active()
            ->latest()
            ->paginate(12);

        return Inertia::render('Public/Services/Index', [
            'services' => $services,
        ]);
    }

    /**
     * Display a single service details.
     */
    public function show(string $slug): Response
    {
        $service = Service::with(['seo', 'faqs', 'testimonials'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Hydrate block contents dynamically
        $content = $service->content ?? [];
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

        $service->setAttribute('content', $hydratedContent);

        return Inertia::render('Public/Services/Show', [
            'service' => $service,
        ]);
    }
}
