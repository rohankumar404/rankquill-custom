<?php

use App\Http\Controllers\PagePreviewController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SeoController;
use App\Models\Blog;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'services' => \Illuminate\Support\Facades\Cache::remember('homepage_services', 86400, function () {
            return Service::active()->orderBy('id')->take(6)->get();
        }),
        'portfolios' => \Illuminate\Support\Facades\Cache::remember('homepage_portfolios', 86400, function () {
            return Portfolio::published()->with('categories')->latest()->take(3)->get();
        }),
        'testimonials' => \Illuminate\Support\Facades\Cache::remember('homepage_testimonials', 86400, function () {
            return Testimonial::latest()->take(6)->get();
        }),
        'blogs' => \Illuminate\Support\Facades\Cache::remember('homepage_blogs', 86400, function () {
            return Blog::published()->with('category')->latest()->take(3)->get();
        }),
    ]);
});

Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt', [SeoController::class, 'robotsTxt']);

Route::get('/pages/preview', [PagePreviewController::class, 'show'])->name('pages.preview');

// Public Blog Routing System
Route::get('/blog/feed', RssFeedController::class)->name('blog.feed');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category}', [PublicBlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{category}/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');

// Public Service Routing System
use App\Http\Controllers\PublicServiceController;

Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [PublicServiceController::class, 'show'])->name('services.show');

// Public Portfolio Routing System
use App\Http\Controllers\PublicPortfolioController;

Route::get('/portfolio', [PublicPortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PublicPortfolioController::class, 'show'])->name('portfolio.show');

// Lead Capture System
use App\Http\Controllers\PublicInquiryController;

Route::post('/contact', [PublicInquiryController::class, 'store'])->name('contact.store');

// Public Newsletter System
use App\Http\Controllers\PublicNewsletterController;

Route::post('/newsletter/subscribe', [PublicNewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [PublicNewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
