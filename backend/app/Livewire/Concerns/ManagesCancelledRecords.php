<?php

namespace App\Livewire\Concerns;

trait ManagesCancelledRecords
{
    public bool $showCancelled = false;

    protected function cancelledQuery($query)
    {
        return $this->showCancelled
            ? $query->cancelledOnly()
            : $query->notCancelled();
    }
}
