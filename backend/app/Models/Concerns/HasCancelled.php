<?php

namespace App\Models\Concerns;

trait HasCancelled
{
    public function initializeHasCancelled(): void
    {
        $this->casts['cancelled'] = 'boolean';
    }

    public function scopeNotCancelled($query)
    {
        return $query->where($this->getTable().'.cancelled', false);
    }

    public function scopeCancelledOnly($query)
    {
        return $query->where($this->getTable().'.cancelled', true);
    }

    public function cancelRecord(): void
    {
        $this->update(['cancelled' => true]);
    }

    public function restoreRecord(): void
    {
        $this->update(['cancelled' => false]);
    }
}
