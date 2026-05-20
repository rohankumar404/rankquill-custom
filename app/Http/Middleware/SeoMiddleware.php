<?php

namespace App\Http\Middleware;

use App\Models\Page;
use App\Services\SeoService;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SeoMiddleware
{
    protected SeoService $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Share SEO metadata for standard GET pages (excluding administrative/api views)
        if ($request->isMethod('GET') && ! $request->routeIs('admin.*') && ! $request->expectsJson()) {
            $seo = null;

            // 1. Resolve from route parameters bound to models using HasSeo
            $route = $request->route();
            if ($route) {
                foreach ($route->parameters() as $parameter) {
                    if (is_object($parameter) && method_exists($parameter, 'seo')) {
                        $seo = $parameter->seo;
                        break;
                    }
                }
            }

            // 2. Fallback to Page matching current request URI
            if (! $seo) {
                $path = trim($request->getPathInfo(), '/');
                $slug = empty($path) ? 'home' : $path;

                if (class_exists(Page::class)) {
                    try {
                        $pageModel = Page::where('slug', $slug)->first();
                        if ($pageModel) {
                            $seo = $pageModel->seo;
                        }
                    } catch (\Throwable $e) {
                        // Fail gracefully if schema is not migrated
                    }
                }
            }

            $seoData = $this->seoService->renderTags($seo);
            $breadcrumbs = $this->seoService->getBreadcrumbs();

            Inertia::share('seo', $seoData);
            Inertia::share('breadcrumbs', $breadcrumbs);
        }

        return $response;
    }
}
