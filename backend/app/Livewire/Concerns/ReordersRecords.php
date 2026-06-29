<?php

namespace App\Livewire\Concerns;

trait ReordersRecords
{
    abstract protected function sortableModelClass(): string;

    public function updateSortOrder(array $orderedIds): void
    {
        $model = $this->sortableModelClass();
        $model::reorder(array_map('intval', $orderedIds));
        session()->flash('status', 'Order updated.');
    }
}
