<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasSeo, HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'description',
        'features',
        'content',
        'pricing',
        'price_starting_at',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'content' => 'array',
        'pricing' => 'array',
        'price_starting_at' => 'decimal:2',
    ];

    /**
     * Scope for active services.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive services.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Polymorphic relation to FAQs.
     */
    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    /**
     * Testimonials associated with the service.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
