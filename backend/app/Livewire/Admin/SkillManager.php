<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Skill;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Skills')]
class SkillManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $name = '';

    public string $category = 'Backend';

    public int $level = 80;

    public string $icon = '';

    public bool $is_active = true;

    protected function sortableModelClass(): string
    {
        return Skill::class;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'category' => 'required|string|max:60',
            'level' => 'required|integer|min:0|max:100',
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
        $s = Skill::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $s->id;
        $this->fill($s->only(['name', 'category', 'level', 'is_active']));
        $this->icon = (string) $s->icon;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Skill::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Skill updated.');
        } else {
            $data['sort_order'] = Skill::nextSortOrder();
            Skill::create($data);
            session()->flash('status', 'Skill created.');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Skill::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Skill cancelled.');
    }

    public function restore(int $id): void
    {
        Skill::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Skill restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'name', 'icon']);
        $this->category = 'Backend';
        $this->level = 80;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.skill-manager', [
            'skills' => $this->cancelledQuery(Skill::query())->orderBy('sort_order')->get(),
        ]);
    }
}
