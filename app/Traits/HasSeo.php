<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Boot the trait to clean up associated SEO metadata on delete.
     */
    protected static function bootHasSeo(): void
    {
        static::deleted(function ($model) {
            if (! method_exists($model, 'isForceDeleting') || $model->isForceDeleting()) {
                $model->seo()->delete();
            }
        });
    }

    /**
     * Get the model's polymorphic SEO metadata.
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_metaable');
    }

    /**
     * Helper to create or update associated SEO meta.
     */
    public function updateSeo(array $data): SeoMeta
    {
        return $this->seo()->updateOrCreate([], $data);
    }
}
