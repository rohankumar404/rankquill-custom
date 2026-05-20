<?php

namespace App\Services;

use App\Models\Script;
use App\Models\Setting;
use App\Models\SmtpSetting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    /**
     * Get all cached settings.
     */
    public function all(): array
    {
        return Cache::rememberForever('settings:all', function () {
            return Setting::all()->mapWithKeys(function ($setting) {
                return [$setting->key => [
                    'value' => $setting->value,
                    'group' => $setting->group,
                ]];
            })->toArray();
        });
    }

    /**
     * Get a specific setting value.
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->all();

        if (array_key_exists($key, $settings)) {
            $value = $settings[$key]['value'];

            // Auto JSON decoding if it starts with [ or {
            if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }

            return $value;
        }

        return $default;
    }

    /**
     * Get all settings in a specific group.
     */
    public function group(string $group): array
    {
        $settings = $this->all();
        $groupSettings = [];

        foreach ($settings as $key => $data) {
            if ($data['group'] === $group) {
                $value = $data['value'];
                if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $value = $decoded;
                    }
                }
                $groupSettings[$key] = $value;
            }
        }

        return $groupSettings;
    }

    /**
     * Set a single setting value.
     */
    public function set(string $key, $value, string $group = 'general'): Setting
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        $this->clearCache();

        return $setting;
    }

    /**
     * Set multiple setting values at once.
     */
    public function setMany(array $settings, string $group = 'general'): void
    {
        DB::transaction(function () use ($settings, $group) {
            foreach ($settings as $key => $value) {
                $this->set($key, $value, $group);
            }
        });

        $this->clearCache();
    }

    /**
     * Get the active SMTP settings.
     */
    public function getSmtp(): ?SmtpSetting
    {
        return Cache::rememberForever('settings:smtp', function () {
            return SmtpSetting::where('is_active', true)->first() ?? SmtpSetting::first();
        });
    }

    /**
     * Update the SMTP setting.
     */
    public function updateSmtp(array $data): SmtpSetting
    {
        $smtp = SmtpSetting::updateOrCreate(
            ['id' => 1],
            $data
        );

        Cache::forget('settings:smtp');

        return $smtp;
    }

    /**
     * Get active scripts, optionally filtered by placement.
     */
    public function getScripts(?string $placement = null)
    {
        if ($placement) {
            return Cache::rememberForever("settings:scripts:{$placement}", function () use ($placement) {
                return Script::where('placement', $placement)
                    ->where('is_active', true)
                    ->get();
            });
        }

        return Cache::rememberForever('settings:scripts', function () {
            return Script::where('is_active', true)->get();
        });
    }

    /**
     * Get active social links.
     */
    public function getSocialLinks()
    {
        return Cache::rememberForever('settings:social_links', function () {
            return SocialLink::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Clear all settings-related caches.
     */
    public function clearCache(): void
    {
        Cache::forget('settings:all');
        Cache::forget('settings:smtp');
        Cache::forget('settings:scripts');
        Cache::forget('settings:scripts:head');
        Cache::forget('settings:scripts:body_start');
        Cache::forget('settings:scripts:body_end');
        Cache::forget('settings:social_links');
    }
}
