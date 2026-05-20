<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_metas';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image_url',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_markup',
    ];

    protected $casts = [
        'schema_markup' => 'array',
    ];

    /**
     * Get the parent seo_metaable model (Page, Blog, Service, Portfolio, etc.).
     */
    public function seo_metaable(): MorphTo
    {
        return $this->morphTo();
    }
}
