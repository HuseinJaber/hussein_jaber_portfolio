<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
