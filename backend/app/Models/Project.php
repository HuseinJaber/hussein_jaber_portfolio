<?php

namespace App\Models;

use App\Models\Concerns\HasCancelled;
use App\Models\Concerns\SortableRecords;
use App\Support\ProjectContribution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasCancelled, SortableRecords;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'contribution_areas' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'cancelled' => 'boolean',
        'sort_order' => 'integer',
        'sites_count' => 'integer',
        'year' => 'integer',
        'experience_id' => 'integer',
    ];

    protected $hidden = [
        'projectCategories',
        'techStacks',
    ];

    protected $appends = [
        'category',
        'categories',
        'tech_stack',
        'contribution_labels',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (blank($project->slug)) {
                $project->slug = Str::slug($project->title).'-'.Str::random(4);
            }
        });
    }

    public function projectCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProjectCategory::class, 'project_project_category')
            ->orderBy('project_categories.sort_order');
    }

    public function techStacks(): BelongsToMany
    {
        return $this->belongsToMany(TechStack::class, 'project_tech_stack')
            ->orderBy('tech_stacks.sort_order');
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    /** @return list<string> */
    public function getCategoriesAttribute(): array
    {
        if ($this->relationLoaded('projectCategories')) {
            return $this->projectCategories->pluck('name')->values()->all();
        }

        return [];
    }

    public function getCategoryAttribute(): string
    {
        return $this->categories[0] ?? 'General';
    }

    /** @return list<string> */
    public function getTechStackAttribute(): array
    {
        if ($this->relationLoaded('techStacks')) {
            return $this->techStacks->pluck('name')->values()->all();
        }

        return [];
    }

    /** @return list<string> */
    public function getContributionLabelsAttribute(): array
    {
        return ProjectContribution::labelsFor($this->contribution_areas);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->notCancelled();
    }
}
