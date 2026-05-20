<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        try {
            if (Schema::hasTable('smtp_settings')) {
                $smtp = smtp_setting();
                if ($smtp && $smtp->is_active) {
                    config([
                        'mail.mailers.smtp.host' => $smtp->host,
                        'mail.mailers.smtp.port' => $smtp->port,
                        'mail.mailers.smtp.username' => $smtp->username,
                        'mail.mailers.smtp.password' => $smtp->password,
                        'mail.mailers.smtp.encryption' => $smtp->encryption,
                        'mail.from.address' => $smtp->from_address,
                        'mail.from.name' => $smtp->from_name,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silence exceptions if DB is not migrated or setup yet
        }
    }
}
