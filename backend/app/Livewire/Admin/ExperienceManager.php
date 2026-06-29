<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Experience;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Experience')]
class ExperienceManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $role = '';

    public string $company = '';

    public string $location = '';

    public string $start_date = '';

    public string $end_date = '';

    public bool $is_current = false;

    public string $description = '';

    protected function sortableModelClass(): string
    {
        return Experience::class;
    }

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
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->creatingNew = true;
    }

    public function edit(int $id): void
    {
        $e = Experience::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $e->id;
        $this->role = $e->role;
        $this->company = $e->company;
        $this->location = (string) $e->location;
        $this->start_date = (string) $e->start_date;
        $this->end_date = (string) $e->end_date;
        $this->is_current = $e->is_current;
        $this->description = (string) $e->description;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Experience::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Experience updated.');
        } else {
            $data['sort_order'] = Experience::nextSortOrder();
            Experience::create($data);
            session()->flash('status', 'Experience created.');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Experience::findOrFail($id)->cancelRecord();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        session()->flash('status', 'Experience cancelled.');
    }

    public function restore(int $id): void
    {
        Experience::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Experience restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'role', 'company', 'location', 'start_date', 'end_date', 'description']);
        $this->is_current = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.experience-manager', [
            'experiences' => $this->cancelledQuery(Experience::query())->orderBy('sort_order')->get(),
        ]);
    }
}
