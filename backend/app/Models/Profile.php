<?php

namespace App\Models;

use App\Casts\SectionCopyCast;
use App\Casts\SectionSettingsCast;
use App\Models\Concerns\HasCancelled;
use App\Support\PortfolioSections;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasCancelled;

    protected $guarded = [];

    protected $casts = [
        'available_for_work' => 'boolean',
        'cancelled' => 'boolean',
        'years_experience' => 'integer',
        'projects_completed' => 'integer',
        'happy_clients' => 'integer',
        'sections' => SectionSettingsCast::class,
        'section_order' => 'array',
        'section_copy' => SectionCopyCast::class,
    ];

    protected static function booted(): void
    {
        static::retrieved(function (Profile $profile) {
            if ($profile->getRawOriginal('sections') === null) {
                $profile->setAttribute('sections', PortfolioSections::defaults());
            }

            if ($profile->getRawOriginal('section_order') === null) {
                $profile->setAttribute('section_order', PortfolioSections::defaultOrder());
            }

            if ($profile->getRawOriginal('section_copy') === null) {
                $profile->setAttribute('section_copy', PortfolioSections::defaultCopy());
            }
        });
    }

    public function isSectionEnabled(string $key): bool
    {
        return (bool) ($this->sections[$key] ?? true);
    }

    public static function current(): self
    {
        return static::query()->notCancelled()->firstOrCreate(
            ['id' => 1],
            ['name' => 'Your Name', 'title' => 'Full Stack Developer']
        );
    }
}
