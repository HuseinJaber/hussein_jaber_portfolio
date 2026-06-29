<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class TechStack extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (TechStack $stack) {
            $stack->name = static::normalizeName($stack->name);

            if (blank($stack->slug)) {
                $stack->slug = Str::slug($stack->name);
            }
        });
    }

    public static function normalizeName(string $name): string
    {
        $name = trim($name);

        if (preg_match('/^laravel(\s+[\d.]+)?$/i', $name)) {
            return 'Laravel';
        }

        return $name;
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_tech_stack');
    }

    public static function idForName(string $name): int
    {
        $name = static::normalizeName($name);
        $slug = Str::slug($name);

        $stack = static::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'sort_order' => static::nextSortOrder()],
        );

        return $stack->id;
    }

    /** @param  list<string>  $names */
    public static function idsForNames(array $names): array
    {
        return collect($names)
            ->map(fn (string $name) => static::idForName($name))
            ->unique()
            ->values()
            ->all();
    }
}
