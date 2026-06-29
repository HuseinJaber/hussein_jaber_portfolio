<?php

namespace App\Casts;

use App\Support\PortfolioSections;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/** @implements CastsAttributes<array<string, array<string, mixed>>, array<string, array<string, mixed>>|string|null> */
class SectionCopyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        $stored = is_string($value) ? json_decode($value, true) : $value;

        return PortfolioSections::resolveCopy(is_array($stored) ? $stored : null);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $clean = PortfolioSections::sanitizeCopy(is_array($value) ? $value : []);

        return json_encode($clean);
    }
}
