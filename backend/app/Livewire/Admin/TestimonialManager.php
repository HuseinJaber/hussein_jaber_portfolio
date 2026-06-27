<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Testimonials')]
class TestimonialManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $role = '';
    public string $company = '';
    public string $avatar = '';
    public string $content = '';
    public int $rating = 5;
    public bool $is_published = true;
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:120',
            'company' => 'nullable|string|max:120',
            'avatar' => 'nullable|string|max:255',
            'content' => 'required|string|max:1500',
            'rating' => 'required|integer|min:1|max:5',
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
        $t = Testimonial::findOrFail($id);
        $this->editingId = $t->id;
        $this->name = $t->name;
        $this->role = (string) $t->role;
        $this->company = (string) $t->company;
        $this->avatar = (string) $t->avatar;
        $this->content = $t->content;
        $this->rating = $t->rating;
        $this->is_published = $t->is_published;
        $this->sort_order = $t->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Testimonial::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Testimonial updated.');
        } else {
            Testimonial::create($data);
            session()->flash('status', 'Testimonial created.');
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Testimonial::findOrFail($id)->delete();
        session()->flash('status', 'Testimonial deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'role', 'company', 'avatar', 'content']);
        $this->rating = 5;
        $this->is_published = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.testimonial-manager', [
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
        ]);
    }
}
