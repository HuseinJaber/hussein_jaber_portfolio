<?php

namespace App\Livewire\Admin;

use App\Models\Skill;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Skills')]
class SkillManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $category = 'Backend';
    public int $level = 80;
    public string $icon = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'category' => 'required|string|max:60',
            'level' => 'required|integer|min:0|max:100',
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
        $s = Skill::findOrFail($id);
        $this->editingId = $s->id;
        $this->fill($s->only(['name', 'category', 'level', 'icon', 'sort_order', 'is_active']));
        $this->icon = (string) $s->icon;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Skill::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Skill updated.');
        } else {
            Skill::create($data);
            session()->flash('status', 'Skill created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Skill::findOrFail($id)->delete();
        session()->flash('status', 'Skill deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'icon']);
        $this->category = 'Backend';
        $this->level = 80;
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.skill-manager', [
            'skills' => Skill::orderBy('category')->orderBy('sort_order')->get(),
        ]);
    }
}
