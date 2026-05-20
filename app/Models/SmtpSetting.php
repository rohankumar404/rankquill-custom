<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SmtpSetting extends Model
{
    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'is_active' => 'boolean',
            'password' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('settings:smtp');
        });

        static::deleted(function () {
            Cache::forget('settings:smtp');
        });
    }
}
