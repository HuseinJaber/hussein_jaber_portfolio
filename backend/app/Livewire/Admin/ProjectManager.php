<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Projects')]
class ProjectManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $category = 'Web';

    public string $short_description = '';

    public string $description = '';

    public string $cover_image = '';

    public string $tech_stack = '';

    public string $live_url = '';

    public string $source_url = '';

    public string $client = '';

    public ?int $year = null;

    public bool $is_featured = false;

    public bool $is_published = true;

    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:160',
            'category' => 'required|string|max:60',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
            'tech_stack' => 'nullable|string|max:255',
            'live_url' => 'nullable|url|max:255',
            'source_url' => 'nullable|url|max:255',
            'client' => 'nullable|string|max:120',
            'year' => 'nullable|integer|min:2000|max:2100',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
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
        $project = Project::findOrFail($id);
        $this->editingId = $project->id;
        $this->title = $project->title;
        $this->category = $project->category;
        $this->short_description = (string) $project->short_description;
        $this->description = (string) $project->description;
        $this->cover_image = (string) $project->cover_image;
        $this->tech_stack = collect($project->tech_stack ?? [])->implode(', ');
        $this->live_url = (string) $project->live_url;
        $this->source_url = (string) $project->source_url;
        $this->client = (string) $project->client;
        $this->year = $project->year;
        $this->is_featured = $project->is_featured;
        $this->is_published = $project->is_published;
        $this->sort_order = $project->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['tech_stack'] = collect(explode(',', $this->tech_stack))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();

        if ($this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Project updated.');
        } else {
            Project::create($data);
            session()->flash('status', 'Project created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Project::findOrFail($id)->delete();
        session()->flash('status', 'Project deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'title', 'short_description', 'description', 'cover_image',
            'tech_stack', 'live_url', 'source_url', 'client', 'year',
        ]);
        $this->category = 'Web';
        $this->is_featured = false;
        $this->is_published = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.project-manager', [
            'projects' => Project::orderBy('sort_order')->get(),
        ]);
    }
}
