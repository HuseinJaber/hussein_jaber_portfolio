<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Models\NewsletterSubscriber;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Newsletter')]
class NewsletterManager extends Component
{
    use ManagesCancelledRecords, WithPagination;

    public function delete(int $id): void
    {
        NewsletterSubscriber::findOrFail($id)->cancelRecord();
        session()->flash('status', 'Subscriber cancelled (kept in admin backup).');
    }

    public function restore(int $id): void
    {
        NewsletterSubscriber::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Subscriber restored.');
    }

    public function toggleActive(int $id): void
    {
        $subscriber = NewsletterSubscriber::notCancelled()->findOrFail($id);
        $subscriber->update(['is_active' => ! $subscriber->is_active]);
        session()->flash('status', $subscriber->is_active ? 'Subscriber reactivated.' : 'Subscriber deactivated.');
    }

    public function render()
    {
        $base = $this->cancelledQuery(NewsletterSubscriber::query());

        return view('livewire.admin.newsletter-manager', [
            'subscribers' => (clone $base)->latest()->paginate(15),
            'activeCount' => NewsletterSubscriber::active()->count(),
            'totalCount' => NewsletterSubscriber::notCancelled()->count(),
        ]);
    }
}
