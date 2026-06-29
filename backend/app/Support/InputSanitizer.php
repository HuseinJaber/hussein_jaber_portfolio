<?php

namespace App\Support;

class InputSanitizer
{
    public static function text(?string $value, int $max = 1000): ?string
    {
        if ($value === null) {
            return null;
        }

        $clean = trim(strip_tags($value));
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $clean) ?? '';

        return mb_substr($clean, 0, $max);
    }

    public static function email(string $value): string
    {
        return strtolower(trim(strip_tags($value)));
    }

    public static function path(string $value): string
    {
        $clean = trim($value);

        return mb_substr($clean, 0, 255);
    }
}
