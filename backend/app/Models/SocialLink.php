<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->notCancelled();
    }
}
