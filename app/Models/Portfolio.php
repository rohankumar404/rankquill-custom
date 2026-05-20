<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
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
        'completed_at',
        'status',
    ];

    protected $casts = [
        'metrics' => 'array',
        'completed_at' => 'date',
    ];

    /**
     * Override source slug column to target the project_title.
     */
    public function slugSourceColumn(): string
    {
        return 'project_title';
    }
}
