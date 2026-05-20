<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicPortfolioController extends Controller
{
    /**
     * Display a listing of published portfolios.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->input('category');

        $portfolios = Portfolio::published()
            ->with(['categories', 'seo'])
            ->when($categorySlug, function ($query, $slug) {
                $query->whereHas('categories', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::whereHas('portfolios', function ($query) {
            $query->published();
        })->get();

        return Inertia::render('Public/Portfolio/Index', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'selectedCategory' => $categorySlug,
        ]);
    }

    /**
     * Display the specified portfolio details.
     */
    public function show(string $slug): Response
    {
        $portfolio = Portfolio::with(['categories', 'seo'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Hydrate block contents dynamically
        $content = $portfolio->content ?? [];
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

        $portfolio->setAttribute('content', $hydratedContent);

        return Inertia::render('Public/Portfolio/Show', [
            'portfolio' => $portfolio,
        ]);
    }
}
