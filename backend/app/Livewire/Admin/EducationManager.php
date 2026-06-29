<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Education;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Education')]
class EducationManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $degree = '';

    public string $institution = '';

    public string $location = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $description = '';

    protected function sortableModelClass(): string
    {
        return Education::class;
    }

    protected function rules(): array
    {
        return [
            'degree' => 'required|string|max:160',
            'institution' => 'required|string|max:160',
            'location' => 'nullable|string|max:120',
            'start_date' => 'nullable|string|max:40',
            'end_date' => 'nullable|string|max:40',
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->creatingNew = true;
    }

    public function edit(int $id): void
    {
        $e = Education::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $e->id;
        $this->degree = $e->degree;
        $this->institution = $e->institution;
        $this->location = (string) $e->location;
        $this->start_date = (string) $e->start_date;
        $this->end_date = (string) $e->end_date;
        $this->description = (string) $e->description;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Education::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Education updated.');
        } else {
            $data['sort_order'] = Education::nextSortOrder();
            Education::create($data);
            session()->flash('status', 'Education created.');
        }
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Education::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Education cancelled.');
    }

    public function restore(int $id): void
    {
        Education::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Education restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'degree', 'institution', 'location', 'start_date', 'end_date', 'description']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.education-manager', [
            'educations' => $this->cancelledQuery(Education::query())->orderBy('sort_order')->get(),
        ]);
    }
}
