<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\TechStack;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Tech Stacks')]
class TechStackManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    protected function sortableModelClass(): string
    {
        return TechStack::class;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $stack = TechStack::findOrFail($id);
        $this->editingId = $stack->id;
        $this->name = $stack->name;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['slug'] = Str::slug($data['name']);

        if ($this->editingId) {
            TechStack::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Tech stack updated.');
        } else {
            $data['sort_order'] = TechStack::nextSortOrder();
            TechStack::create($data);
            session()->flash('status', 'Tech stack created.');
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        TechStack::findOrFail($id)->cancelRecord();
        session()->flash('status', 'Tech stack cancelled.');
    }

    public function restore(int $id): void
    {
        TechStack::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Tech stack restored.');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->showModal = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.tech-stack-manager', [
            'stacks' => $this->cancelledQuery(TechStack::query())
                ->withCount('projects')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
