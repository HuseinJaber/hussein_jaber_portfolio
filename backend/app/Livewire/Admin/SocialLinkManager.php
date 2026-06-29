<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\SocialLink;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Social Links')]
class SocialLinkManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $platform = '';

    public string $label = '';

    public string $url = '';

    public string $icon = '';

    public bool $is_active = true;

    protected function sortableModelClass(): string
    {
        return SocialLink::class;
    }

    protected function rules(): array
    {
        return [
            'platform' => 'required|string|max:60',
            'label' => 'nullable|string|max:60',
            'url' => 'required|url|max:255',
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
        $s = SocialLink::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $s->id;
        $this->platform = $s->platform;
        $this->label = (string) $s->label;
        $this->url = $s->url;
        $this->icon = (string) $s->icon;
        $this->is_active = $s->is_active;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            SocialLink::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Social link updated.');
        } else {
            $data['sort_order'] = SocialLink::nextSortOrder();
            SocialLink::create($data);
            session()->flash('status', 'Social link created.');
        }
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        SocialLink::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Social link cancelled.');
    }

    public function restore(int $id): void
    {
        SocialLink::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Social link restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'platform', 'label', 'url', 'icon']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.social-link-manager', [
            'links' => $this->cancelledQuery(SocialLink::query())->orderBy('sort_order')->get(),
        ]);
    }
}
