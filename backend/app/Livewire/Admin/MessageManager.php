<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Messages')]
class MessageManager extends Component
{
    use WithPagination;

    public ?int $selectedId = null;

    public function select(int $id): void
    {
        $this->selectedId = $id;
        ContactMessage::whereKey($id)->update(['is_read' => true]);
    }

    public function markUnread(int $id): void
    {
        ContactMessage::whereKey($id)->update(['is_read' => false]);
    }

    public function delete(int $id): void
    {
        ContactMessage::findOrFail($id)->delete();
        if ($this->selectedId === $id) {
            $this->selectedId = null;
        }
        session()->flash('status', 'Message deleted.');
    }

    public function getSelectedProperty(): ?ContactMessage
    {
        return $this->selectedId ? ContactMessage::find($this->selectedId) : null;
    }

    public function render()
    {
        return view('livewire.admin.message-manager', [
            'messages' => ContactMessage::latest()->paginate(10),
        ]);
    }
}
