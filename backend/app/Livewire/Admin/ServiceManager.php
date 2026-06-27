<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Services')]
class ServiceManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $description = '';
    public string $icon = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:60',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $s = Service::findOrFail($id);
        $this->editingId = $s->id;
        $this->title = $s->title;
        $this->description = (string) $s->description;
        $this->icon = (string) $s->icon;
        $this->sort_order = $s->sort_order;
        $this->is_active = $s->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Service::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Service updated.');
        } else {
            Service::create($data);
            session()->flash('status', 'Service created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Service::findOrFail($id)->delete();
        session()->flash('status', 'Service deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'description', 'icon']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.service-manager', [
            'services' => Service::orderBy('sort_order')->get(),
        ]);
    }
}
