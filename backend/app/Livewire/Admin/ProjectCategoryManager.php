<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\ProjectCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Project Categories')]
class ProjectCategoryManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    protected function sortableModelClass(): string
    {
        return ProjectCategory::class;
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
        $category = ProjectCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['slug'] = Str::slug($data['name']);

        if ($this->editingId) {
            ProjectCategory::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Category updated.');
        } else {
            $data['sort_order'] = ProjectCategory::nextSortOrder();
            ProjectCategory::create($data);
            session()->flash('status', 'Category created.');
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        ProjectCategory::findOrFail($id)->cancelRecord();
        session()->flash('status', 'Category cancelled.');
    }

    public function restore(int $id): void
    {
        ProjectCategory::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Category restored.');
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
        return view('livewire.admin.project-category-manager', [
            'categories' => $this->cancelledQuery(ProjectCategory::query())
                ->withCount('projects')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
