<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];
}
