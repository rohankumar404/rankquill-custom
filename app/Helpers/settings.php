<?php

use App\Services\SettingsService;

if (! function_exists('setting')) {
    /**
     * Get a specific setting value.
     */
    function setting(string $key, $default = null)
    {
        return app(SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('settings_group')) {
    /**
     * Get all settings in a specific group.
     */
    function settings_group(string $group): array
    {
        return app(SettingsService::class)->group($group);
    }
}

if (! function_exists('smtp_setting')) {
    /**
     * Get the active SMTP configuration.
     */
    function smtp_setting()
    {
        return app(SettingsService::class)->getSmtp();
    }
}

if (! function_exists('active_scripts')) {
    /**
     * Get active scripts, optionally filtered by placement.
     */
    function active_scripts(?string $placement = null)
    {
        return app(SettingsService::class)->getScripts($placement);
    }
}

if (! function_exists('active_social_links')) {
    /**
     * Get active social links.
     */
    function active_social_links()
    {
        return app(SettingsService::class)->getSocialLinks();
    }
}
