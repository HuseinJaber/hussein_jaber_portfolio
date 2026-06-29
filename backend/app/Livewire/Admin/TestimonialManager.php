<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesCancelledRecords;
use App\Livewire\Concerns\ReordersRecords;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Testimonials')]
class TestimonialManager extends Component
{
    use ManagesCancelledRecords, ReordersRecords;

    public ?int $editingId = null;

    public bool $creatingNew = false;

    public string $name = '';

    public string $role = '';

    public string $company = '';

    public string $avatar = '';

    public string $content = '';

    public int $rating = 5;

    public bool $is_published = true;

    protected function sortableModelClass(): string
    {
        return Testimonial::class;
    }

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
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->creatingNew = true;
    }

    public function edit(int $id): void
    {
        $t = Testimonial::findOrFail($id);
        $this->creatingNew = false;
        $this->editingId = $t->id;
        $this->name = $t->name;
        $this->role = (string) $t->role;
        $this->company = (string) $t->company;
        $this->avatar = (string) $t->avatar;
        $this->content = $t->content;
        $this->rating = $t->rating;
        $this->is_published = $t->is_published;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Testimonial::findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Testimonial updated.');
        } else {
            $data['sort_order'] = Testimonial::nextSortOrder();
            Testimonial::create($data);
            session()->flash('status', 'Testimonial created.');
        }
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Testimonial::findOrFail($id)->cancelRecord();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        session()->flash('status', 'Testimonial cancelled.');
    }

    public function restore(int $id): void
    {
        Testimonial::cancelledOnly()->findOrFail($id)->restoreRecord();
        session()->flash('status', 'Testimonial restored.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'creatingNew', 'name', 'role', 'company', 'avatar', 'content']);
        $this->rating = 5;
        $this->is_published = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.testimonial-manager', [
            'testimonials' => $this->cancelledQuery(Testimonial::query())->orderBy('sort_order')->get(),
        ]);
    }
}
