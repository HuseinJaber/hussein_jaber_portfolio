<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';

    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
