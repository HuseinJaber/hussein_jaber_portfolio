<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Projects')]
class ProjectManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    protected function sortableModelClass(): string
    {
        return Project::class;
    }

    public function delete(int $id): void
    {
        Project::findOrFail($id)->cancelRecord();
        session()->flash('status', 'Project cancelled (hidden from site, kept in admin).');
    }

    public function restore(int $id): void
    {
        Project::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Project restored.');
    }

    public function render()
    {
        $query = Project::query()->with([
            'experience:id,company,role',
            'projectCategories:id,name',
            'techStacks:id,name',
        ]);

        return view('livewire.admin.project-manager', [
            'projects' => $this->cancelledQuery($query)->orderBy('sort_order')->get(),
        ]);
    }
}
