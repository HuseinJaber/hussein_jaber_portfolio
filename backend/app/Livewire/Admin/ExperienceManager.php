<?php

namespace App\Livewire\Admin;

use App\Models\Experience;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Experience')]
class ExperienceManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $role = '';
    public string $company = '';
    public string $location = '';
    public string $start_date = '';
    public string $end_date = '';
    public bool $is_current = false;
    public string $description = '';
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'role' => 'required|string|max:120',
            'company' => 'required|string|max:120',
            'location' => 'nullable|string|max:120',
            'start_date' => 'nullable|string|max:40',
            'end_date' => 'nullable|string|max:40',
            'is_current' => 'boolean',
            'description' => 'nullable|string|max:2000',
            'sort_order' => 'integer',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $e = Experience::findOrFail($id);
        $this->editingId = $e->id;
        $this->role = $e->role;
        $this->company = $e->company;
        $this->location = (string) $e->location;
        $this->start_date = (string) $e->start_date;
        $this->end_date = (string) $e->end_date;
        $this->is_current = $e->is_current;
        $this->description = (string) $e->description;
        $this->sort_order = $e->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Experience::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Experience updated.');
        } else {
            Experience::create($data);
            session()->flash('status', 'Experience created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Experience::findOrFail($id)->delete();
        session()->flash('status', 'Experience deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'role', 'company', 'location', 'start_date', 'end_date', 'description']);
        $this->is_current = false;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.experience-manager', [
            'experiences' => Experience::orderByDesc('sort_order')->get(),
        ]);
    }
}
