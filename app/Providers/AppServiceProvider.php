<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function ($app) {
            return new SettingsService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Rate Limiters
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('newsletter', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        try {
            if (Schema::hasTable('smtp_settings')) {
                $smtp = smtp_setting();
                if ($smtp && ($smtp['is_active'] ?? false)) {
                    config([
                        'mail.mailers.smtp.host' => $smtp['host'] ?? '',
                        'mail.mailers.smtp.port' => $smtp['port'] ?? 587,
                        'mail.mailers.smtp.username' => $smtp['username'] ?? '',
                        'mail.mailers.smtp.password' => $smtp['password'] ?? '',
                        'mail.mailers.smtp.encryption' => $smtp['encryption'] ?? 'tls',
                        'mail.from.address' => $smtp['from_address'] ?? '',
                        'mail.from.name' => $smtp['from_name'] ?? '',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silence exceptions if DB is not migrated or setup yet
        }

        // Register model hooks for clearing cache
        try {
            \App\Models\Service::saved(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_services'));
            \App\Models\Service::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_services'));
            
            \App\Models\Portfolio::saved(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_portfolios'));
            \App\Models\Portfolio::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_portfolios'));
            
            \App\Models\Testimonial::saved(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_testimonials'));
            \App\Models\Testimonial::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_testimonials'));
            
            \App\Models\Blog::saved(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_blogs'));
            \App\Models\Blog::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('homepage_blogs'));
        } catch (\Throwable $e) {
            // Silence exceptions if DB is not migrated or setup yet
        }
    }
}
