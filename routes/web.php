<?php

use App\Http\Controllers\PagePreviewController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
