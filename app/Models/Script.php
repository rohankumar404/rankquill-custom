<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Script extends Model
{
    protected $fillable = [
        'name',
        'placement',
        'code',
        'is_active',
        'requires_consent',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_consent' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Script $script) {
            Cache::forget('settings:scripts');
            Cache::forget("settings:scripts:{$script->placement}");
        });

        static::deleted(function (Script $script) {
            Cache::forget('settings:scripts');
            Cache::forget("settings:scripts:{$script->placement}");
        });
    }
}
