<?php

namespace App\Models\Concerns;

trait SortableRecords
{
    public static function reorder(array $orderedIds): void
    {
        foreach (array_values($orderedIds) as $index => $id) {
            static::query()->whereKey($id)->update(['sort_order' => $index + 1]);
        }
    }

    public static function nextSortOrder(): int
    {
        return (int) static::query()->max('sort_order') + 1;
    }
}
