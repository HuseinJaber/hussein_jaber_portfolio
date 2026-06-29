<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Services')]
class ServiceManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $title = '';

    public string $description = '';

    public string $icon = '';

    public bool $is_active = true;

    protected function sortableModelClass(): string
    {
        return Service::class;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:60',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->creatingNew = true;
    }

    public function edit(int $id): void
    {
        $s = Service::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $s->id;
        $this->title = $s->title;
        $this->description = (string) $s->description;
        $this->icon = (string) $s->icon;
        $this->is_active = $s->is_active;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Service::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Service updated.');
        } else {
            $data['sort_order'] = Service::nextSortOrder();
            Service::create($data);
            session()->flash('status', 'Service created.');
        }
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Service::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Service cancelled.');
    }

    public function restore(int $id): void
    {
        Service::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Service restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'title', 'description', 'icon']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.service-manager', [
            'services' => $this->cancelledQuery(Service::query())->orderBy('sort_order')->get(),
        ]);
    }
}
