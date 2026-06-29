<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasCancelled;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'cancelled' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->notCancelled();
    }
}
