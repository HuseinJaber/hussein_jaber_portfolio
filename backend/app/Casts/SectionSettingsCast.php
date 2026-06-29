<?php

namespace App\Casts;

use App\Support\PortfolioSections;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/** @implements CastsAttributes<array<string, bool>, array<string, bool>|string|null> */
class SectionSettingsCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        $stored = is_string($value) ? json_decode($value, true) : $value;

        return PortfolioSections::resolve(is_array($stored) ? $stored : null);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $clean = PortfolioSections::sanitize(is_array($value) ? $value : []);

        return json_encode($clean);
    }
}
