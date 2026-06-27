<?php

namespace App\Livewire\Admin;

use App\Models\SocialLink;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Social Links')]
class SocialLinkManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $platform = '';

    public string $label = '';

    public string $url = '';

    public string $icon = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'platform' => 'required|string|max:60',
            'label' => 'nullable|string|max:60',
            'url' => 'required|url|max:255',
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
        $s = SocialLink::findOrFail($id);
        $this->editingId = $s->id;
        $this->platform = $s->platform;
        $this->label = (string) $s->label;
        $this->url = $s->url;
        $this->icon = (string) $s->icon;
        $this->sort_order = $s->sort_order;
        $this->is_active = $s->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            SocialLink::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Social link updated.');
        } else {
            SocialLink::create($data);
            session()->flash('status', 'Social link created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        SocialLink::findOrFail($id)->delete();
        session()->flash('status', 'Social link deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'platform', 'label', 'url', 'icon']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.social-link-manager', [
            'links' => SocialLink::orderBy('sort_order')->get(),
        ]);
    }
}
