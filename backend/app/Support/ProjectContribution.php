<?php

namespace App\Support;

class ProjectContribution
{
    /** @return array<string, string> */
    public static function definitions(): array
    {
        return config('portfolio_contribution', []);
    }

    /** @return list<string> */
    public static function keys(): array
    {
        return array_keys(self::definitions());
    }

    /** @param  list<string>|null  $areas */
    public static function sanitize(?array $areas): array
    {
        $valid = self::keys();

        if (! is_array($areas) || $areas === []) {
            return ['frontend', 'backend'];
        }

        $clean = [];

        foreach ($areas as $area) {
            if (is_string($area) && in_array($area, $valid, true) && ! in_array($area, $clean, true)) {
                $clean[] = $area;
            }
        }

        return $clean !== [] ? $clean : ['frontend', 'backend'];
    }

    /** @param  list<string>|null  $areas */
    public static function labelsFor(?array $areas): array
    {
        $definitions = self::definitions();

        return collect(self::sanitize($areas))
            ->map(fn (string $key) => $definitions[$key] ?? $key)
            ->values()
            ->all();
    }
}
