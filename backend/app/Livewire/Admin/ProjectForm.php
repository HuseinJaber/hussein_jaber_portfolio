<?php

namespace App\Livewire\Admin;

use App\Models\Experience;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\TechStack;
use App\Support\ProjectContribution;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ProjectForm extends Component
{
    public ?int $editingId = null;

    public string $title = '';

    /** @var list<int> */
    public array $category_ids = [];

    /** @var list<int> */
    public array $tech_stack_ids = [];

    public string $engagement_type = 'development';

    /** @var list<string> */
    public array $contribution_areas = ['frontend', 'backend'];

    public string $work_context = 'none';

    public ?int $experience_id = null;

    public string $short_description = '';

    public string $description = '';

    public string $cover_image = '';

    public string $live_url = '';

    public string $source_url = '';

    public string $client = '';

    public ?int $year = null;

    public bool $is_featured = false;

    public bool $is_published = true;

    public function mount(?Project $project = null): void
    {
        if ($project) {
            $project->load(['projectCategories:id', 'techStacks:id']);
            $this->editingId = $project->id;
            $this->title = $project->title;
            $this->category_ids = $project->projectCategories->pluck('id')->all();
            $this->tech_stack_ids = $project->techStacks->pluck('id')->all();
            $this->engagement_type = $project->engagement_type ?? 'development';
            $this->contribution_areas = ProjectContribution::sanitize($project->contribution_areas);
            $this->work_context = $project->work_context ?? 'none';
            $this->experience_id = $project->experience_id;
            $this->short_description = (string) $project->short_description;
            $this->description = (string) $project->description;
            $this->cover_image = (string) $project->cover_image;
            $this->live_url = (string) $project->live_url;
            $this->source_url = (string) $project->source_url;
            $this->client = (string) $project->client;
            $this->year = $project->year;
            $this->is_featured = $project->is_featured;
            $this->is_published = $project->is_published;

            return;
        }

        $firstCategory = ProjectCategory::notCancelled()->orderBy('sort_order')->value('id');
        $this->category_ids = $firstCategory ? [$firstCategory] : [];
        $this->contribution_areas = ['frontend', 'backend'];
        $this->applyDefaultExperienceLink();
    }

    public function pageTitle(): string
    {
        return $this->editingId ? 'Edit project' : 'Add project';
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:160',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'integer|exists:project_categories,id',
            'tech_stack_ids' => 'nullable|array',
            'tech_stack_ids.*' => 'integer|exists:tech_stacks,id',
            'engagement_type' => 'required|in:development,support',
            'contribution_areas' => 'required|array|min:1',
            'contribution_areas.*' => 'string|in:'.implode(',', ProjectContribution::keys()),
            'work_context' => 'required|in:none,company,freelance',
            'experience_id' => 'nullable|integer|exists:experiences,id|required_if:work_context,company',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
            'live_url' => 'nullable|url|max:255',
            'source_url' => 'nullable|url|max:255',
            'client' => 'nullable|string|max:120',
            'year' => 'nullable|integer|min:2000|max:2100',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $categoryIds = array_values(array_unique(array_map('intval', $data['category_ids'])));
        $techStackIds = array_values(array_unique(array_map('intval', $data['tech_stack_ids'] ?? [])));

        unset($data['category_ids'], $data['tech_stack_ids']);

        $data['contribution_areas'] = ProjectContribution::sanitize($data['contribution_areas'] ?? []);

        if ($data['work_context'] !== 'company') {
            $data['experience_id'] = null;
        }

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $project->update($data);
            session()->flash('status', 'Project updated.');
        } else {
            $data['sort_order'] = Project::nextSortOrder();
            $project = Project::create($data);
            session()->flash('status', 'Project created.');
        }

        $project->projectCategories()->sync($categoryIds);
        $project->techStacks()->sync($techStackIds);

        $this->redirect(route('admin.projects'), navigate: true);
    }

    private function applyDefaultExperienceLink(): void
    {
        $webAddicts = Experience::webAddicts();

        if ($webAddicts) {
            $this->work_context = 'company';
            $this->experience_id = $webAddicts->id;

            return;
        }

        $this->work_context = 'none';
        $this->experience_id = null;
    }

    public function render()
    {
        return view('livewire.admin.project-form-page', [
            'experiences' => Experience::notCancelled()->orderBy('sort_order')->get(['id', 'company', 'role']),
            'categories' => ProjectCategory::notCancelled()->orderBy('sort_order')->get(['id', 'name']),
            'techStacks' => TechStack::notCancelled()->orderBy('sort_order')->get(['id', 'name']),
            'contributionOptions' => ProjectContribution::definitions(),
        ])->title($this->pageTitle());
    }
}
