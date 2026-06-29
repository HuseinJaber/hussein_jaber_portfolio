<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'cancelled' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->notCancelled();
    }
}
