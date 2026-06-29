<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasCancelled;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'cancelled' => 'boolean',
    ];
}
