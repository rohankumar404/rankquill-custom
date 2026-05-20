<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SocialLink extends Model
{
    protected $fillable = [
        'platform_name',
        'url',
        'icon_svg',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('settings:social_links');
        });

        static::deleted(function () {
            Cache::forget('settings:social_links');
        });
    }
}
