<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait to generate unique slugs on model events.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
            $model->generateSlug();
        });
    }

    /**
     * Get the source column name for the slug.
     */
    public function slugSourceColumn(): string
    {
        return 'title'; // Can be overridden in models (e.g. return 'name')
    }

    /**
     * Get the target slug column name.
     */
    public function slugColumn(): string
    {
        return 'slug';
    }

    /**
     * Generate the unique slug.
     */
    public function generateSlug(): void
    {
        $source = $this->slugSourceColumn();
        $target = $this->slugColumn();

        if (empty($this->{$source})) {
            return;
        }

        if (empty($this->{$target})) {
            $slug = Str::slug($this->{$source});
            $originalSlug = $slug;
            $count = 1;

            while ($this->slugExists($slug)) {
                $slug = $originalSlug.'-'.$count++;
            }

            $this->{$target} = $slug;
        }
    }

    /**
     * Check if the generated slug already exists.
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::where($this->slugColumn(), $slug);

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        return $query->exists();
    }
}
