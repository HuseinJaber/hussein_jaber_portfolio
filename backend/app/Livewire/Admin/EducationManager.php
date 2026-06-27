<?php

namespace App\Livewire\Admin;

use App\Models\Education;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Education')]
class EducationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $degree = '';
    public string $institution = '';
    public string $location = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $description = '';
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'degree' => 'required|string|max:160',
            'institution' => 'required|string|max:160',
            'location' => 'nullable|string|max:120',
            'start_date' => 'nullable|string|max:40',
            'end_date' => 'nullable|string|max:40',
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
        $e = Education::findOrFail($id);
        $this->editingId = $e->id;
        $this->degree = $e->degree;
        $this->institution = $e->institution;
        $this->location = (string) $e->location;
        $this->start_date = (string) $e->start_date;
        $this->end_date = (string) $e->end_date;
        $this->description = (string) $e->description;
        $this->sort_order = $e->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Education::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Education updated.');
        } else {
            Education::create($data);
            session()->flash('status', 'Education created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Education::findOrFail($id)->delete();
        session()->flash('status', 'Education deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'degree', 'institution', 'location', 'start_date', 'end_date', 'description']);
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.education-manager', [
            'educations' => Education::orderByDesc('sort_order')->get(),
        ]);
    }
}
