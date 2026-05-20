<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\PagePreviewController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\NewsletterController;

Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/test-smtp', [SettingsController::class, 'testSmtp'])->name('settings.test-smtp');
Route::get('/activity-logs', ActivityLogController::class)->name('activity-logs');

Route::resource('pages', PageController::class);
Route::post('/pages/{page}/preview', [PagePreviewController::class, 'generate'])->name('pages.generate-preview');
Route::resource('faqs', FaqController::class);
Route::resource('testimonials', TestimonialController::class);
Route::resource('team-members', TeamMemberController::class);

Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('blogs', BlogController::class);
Route::resource('services', ServiceController::class);
Route::resource('portfolios', PortfolioController::class);

Route::get('/inquiries/export', [InquiryController::class, 'exportCsv'])->name('inquiries.export');
Route::resource('inquiries', InquiryController::class)->only(['index', 'update', 'destroy']);

Route::get('/newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
Route::resource('newsletter', NewsletterController::class)->only(['index', 'destroy']);
