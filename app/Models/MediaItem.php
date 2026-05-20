<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'name',
        'file_name',
        'path',
        'mime_type',
        'size',
        'alt_text',
        'conversions',
    ];

    protected $casts = [
        'conversions' => 'array',
    ];

    protected $appends = [
        'url',
        'conversions_urls',
    ];

    /**
     * Get the folder containing this file.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * Get the absolute public URL of the main file.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Get absolute public URLs for all responsive conversions.
     */
    public function getConversionsUrlsAttribute(): array
    {
        if (! $this->conversions) {
            return [];
        }

        $urls = [];
        foreach ($this->conversions as $key => $path) {
            $urls[$key] = Storage::disk('public')->url($path);
        }

        return $urls;
    }
}
