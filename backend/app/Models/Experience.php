<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_current' => 'boolean',
        'sort_order' => 'integer',
    ];
}
