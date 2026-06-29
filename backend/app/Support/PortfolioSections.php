<?php

namespace App\Support;

class PortfolioSections
{
    /** @return array<string, array{label: string, description: string, default: bool}> */
    public static function definitions(): array
    {
        return config('portfolio_sections', []);
    }

    /** @return list<string> */
    public static function defaultOrder(): array
    {
        return array_keys(self::definitions());
    }

    /** @return list<string> */
    public static function resolveOrder(?array $stored): array
    {
        $valid = self::defaultOrder();

        if (! is_array($stored) || $stored === []) {
            return $valid;
        }

        $ordered = [];

        foreach ($stored as $key) {
            if (is_string($key) && in_array($key, $valid, true) && ! in_array($key, $ordered, true)) {
                $ordered[] = $key;
            }
        }

        foreach ($valid as $key) {
            if (! in_array($key, $ordered, true)) {
                $ordered[] = $key;
            }
        }

        return $ordered;
    }

    /** @param  list<string>  $submitted */
    public static function sanitizeOrder(array $submitted): array
    {
        return self::resolveOrder($submitted);
    }

    /** @return array<string, array{nav_label: string, eyebrow: string, title: string, subtitle: string|null, align: string}> */
    public static function defaultCopy(): array
    {
        $copy = [];

        foreach (self::definitions() as $key => $meta) {
            $defaults = $meta['copy'] ?? [];
            $copy[$key] = [
                'nav_label' => (string) ($defaults['nav_label'] ?? $meta['label'] ?? $key),
                'eyebrow' => (string) ($defaults['eyebrow'] ?? $meta['label'] ?? $key),
                'title' => (string) ($defaults['title'] ?? $meta['label'] ?? $key),
                'subtitle' => isset($defaults['subtitle']) ? (string) $defaults['subtitle'] : null,
                'align' => (string) ($defaults['align'] ?? 'center'),
            ];
        }

        return $copy;
    }

    /** @return array<string, array{nav_label: string, eyebrow: string, title: string, subtitle: string|null, align: string}> */
    public static function resolveCopy(?array $stored): array
    {
        $resolved = self::defaultCopy();

        if (! is_array($stored)) {
            return $resolved;
        }

        foreach ($resolved as $key => $defaults) {
            if (! isset($stored[$key]) || ! is_array($stored[$key])) {
                continue;
            }

            $submitted = $stored[$key];

            if (isset($submitted['nav_label']) && is_string($submitted['nav_label'])) {
                $resolved[$key]['nav_label'] = trim($submitted['nav_label']);
            }

            if (isset($submitted['eyebrow']) && is_string($submitted['eyebrow'])) {
                $resolved[$key]['eyebrow'] = trim($submitted['eyebrow']);
            }

            if (isset($submitted['title']) && is_string($submitted['title'])) {
                $resolved[$key]['title'] = trim($submitted['title']);
            }

            if (array_key_exists('subtitle', $submitted)) {
                $subtitle = is_string($submitted['subtitle']) ? trim($submitted['subtitle']) : null;
                $resolved[$key]['subtitle'] = $subtitle === '' ? null : $subtitle;
            }

            if (isset($submitted['align']) && in_array($submitted['align'], ['center', 'left'], true)) {
                $resolved[$key]['align'] = $submitted['align'];
            }
        }

        return $resolved;
    }

    /** @param  array<string, array<string, mixed>>  $submitted */
    public static function sanitizeCopy(array $submitted): array
    {
        return self::resolveCopy($submitted);
    }

    /** @return array<string, bool> */
    public static function defaults(): array
    {
        $defaults = [];

        foreach (self::definitions() as $key => $meta) {
            $defaults[$key] = (bool) ($meta['default'] ?? true);
        }

        return $defaults;
    }

    /** @return array<string, bool> */
    public static function resolve(?array $stored): array
    {
        $resolved = self::defaults();

        if (! is_array($stored)) {
            return $resolved;
        }

        foreach ($resolved as $key => $default) {
            if (array_key_exists($key, $stored)) {
                $resolved[$key] = (bool) $stored[$key];
            }
        }

        return $resolved;
    }

    public static function isEnabled(?array $stored, string $key): bool
    {
        return self::resolve($stored)[$key] ?? true;
    }

    /** @param  array<string, bool>  $submitted */
    public static function sanitize(array $submitted): array
    {
        $clean = self::defaults();

        foreach ($clean as $key => $default) {
            if (array_key_exists($key, $submitted)) {
                $clean[$key] = (bool) $submitted[$key];
            }
        }

        return $clean;
    }
}
