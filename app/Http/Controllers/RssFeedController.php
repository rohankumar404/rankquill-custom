<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Http\Response;

class RssFeedController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Generate the RSS feed for the blog system.
     */
    public function __invoke(): Response
    {
        $feedContent = $this->blogService->generateRssFeed();

        return response($feedContent, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
