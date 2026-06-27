<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if (blank($service->slug)) {
                $service->slug = Str::slug($service->title).'-'.Str::random(4);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
