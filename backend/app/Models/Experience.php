<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'is_current' => 'boolean',
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public static function webAddicts(): ?self
    {
        return static::query()
            ->notCancelled()
            ->where('company', 'TheWebAddicts')
            ->where('role', 'Full Stack Developer')
            ->first();
    }
}
