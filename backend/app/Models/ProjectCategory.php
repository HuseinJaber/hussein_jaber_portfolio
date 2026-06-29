<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ProjectCategory extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProjectCategory $category) {
            $category->name = static::normalizeName($category->name);
            $category->slug = Str::slug($category->name);
        });
    }

    public static function normalizeName(string $name): string
    {
        $name = trim($name);

        return match (strtolower($name)) {
            'e-commerce' => 'E-Commerce',
            'corporate' => 'Corporate',
            'portfolio' => 'Portfolio',
            'web app' => 'Web App',
            default => $name,
        };
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_project_category');
    }

    public static function idForName(string $name): int
    {
        $name = static::normalizeName($name);
        $slug = Str::slug($name);

        $category = static::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'sort_order' => static::nextSortOrder()],
        );

        return $category->id;
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
