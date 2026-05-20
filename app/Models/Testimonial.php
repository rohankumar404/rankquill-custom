<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_name',
        'client_position',
        'client_company',
        'avatar_path',
        'testimonial',
        'rating',
        'portfolio_id',
        'service_id',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the portfolio associated with the testimonial.
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Get the service associated with the testimonial.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
