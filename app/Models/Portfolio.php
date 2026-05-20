<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasSeo, HasSlug, SoftDeletes;

    protected $table = 'portfolios';

    protected $fillable = [
        'client_name',
        'project_title',
        'slug',
        'challenge',
        'approach',
        'results',
        'metrics',
        'before_image_path',
        'after_image_path',
        'gallery',
        'video_url',
        'content',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'metrics' => 'array',
        'gallery' => 'array',
        'content' => 'array',
        'completed_at' => 'date',
    ];

    /**
     * Scope for published portfolios.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for draft portfolios.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Categories associated with the portfolio.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_portfolio');
    }

    /**
     * Override source slug column to target the project_title.
     */
    public function slugSourceColumn(): string
    {
        return 'project_title';
    }
}
