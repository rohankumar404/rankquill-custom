<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    protected SeoService $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Serve dynamic sitemap.xml.
     */
    public function sitemap()
    {
        return $this->seoService->generateSitemap()->toResponse(request());
    }

    /**
     * Serve dynamic robots.txt.
     */
    public function robotsTxt(): Response
    {
        return response($this->seoService->generateRobotsTxt())
            ->header('Content-Type', 'text/plain');
    }
}
