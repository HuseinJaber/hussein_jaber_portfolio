<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Messages')]
class MessageManager extends Component
{
    use ManagesCancelledRecords, WithPagination;

    public ?int $selectedId = null;

    public bool $showReplyModal = false;

    public string $replySubject = '';

    public string $replyBody = '';

    public function select(int $id): void
    {
        $this->selectedId = $id;
        $this->showReplyModal = false;
        ContactMessage::whereKey($id)->update(['is_read' => true]);
    }

    public function openReply(): void
    {
        $message = $this->selected;

        if (! $message || $message->cancelled) {
            return;
        }

        $this->replySubject = $message->subject
            ? 'Re: '.$message->subject
            : 'Re: Your message from '.$message->name;

        $this->replyBody = '';
        $this->resetValidation();
        $this->showReplyModal = true;
    }

    public function closeReplyModal(): void
    {
        $this->showReplyModal = false;
        $this->reset(['replySubject', 'replyBody']);
        $this->resetValidation();
    }

    public function sendReply(): void
    {
        $message = ContactMessage::findOrFail($this->selectedId);

        $validated = $this->validate([
            'replySubject' => 'required|string|max:255',
            'replyBody' => 'required|string|max:10000',
        ]);

        Mail::to($message->email)->send(new ContactReplyMail(
            $message,
            $validated['replySubject'],
            $validated['replyBody'],
        ));

        $message->update(['is_read' => true]);

        session()->flash('status', 'Reply sent to '.$message->email.'.');
        $this->closeReplyModal();
    }

    public function markUnread(int $id): void
    {
        ContactMessage::whereKey($id)->update(['is_read' => false]);
    }

    public function delete(int $id): void
    {
        ContactMessage::findOrFail($id)->cancelRecord();
        if ($this->selectedId === $id) {
            $this->selectedId = null;
            $this->closeReplyModal();
        }
        session()->flash('status', 'Message cancelled (kept in admin backup).');
    }

    public function restore(int $id): void
    {
        ContactMessage::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Message restored.');
    }

    public function getSelectedProperty(): ?ContactMessage
    {
        return $this->selectedId ? ContactMessage::find($this->selectedId) : null;
    }

    public function render()
    {
        return view('livewire.admin.message-manager', [
            'messages' => $this->cancelledQuery(ContactMessage::query())->latest()->paginate(10),
        ]);
    }
}
