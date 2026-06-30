<?php

namespace App\Livewire\Admin;

use App\Models\Profile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Profile')]
class ProfileManager extends Component
{
    public Profile $profile;

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|string|max:160')]
    public string $title = '';

    #[Validate('nullable|string|max:255')]
    public ?string $headline = '';

    #[Validate('nullable|string|max:500')]
    public ?string $bio = '';

    #[Validate('nullable|string')]
    public ?string $about = '';

    #[Validate('nullable|email|max:180')]
    public ?string $email = '';

    #[Validate('nullable|string|max:60')]
    public ?string $phone = '';

    #[Validate('nullable|string|max:120')]
    public ?string $location = '';

    #[Validate('nullable|string|max:255')]
    public ?string $resume_url = '';

    #[Validate('nullable|string|max:80')]
    public ?string $cv_download_label = '';

    #[Validate('nullable|string|max:80')]
    public ?string $cv_view_label = '';

    #[Validate('integer|min:0|max:80')]
    public int $years_experience = 0;

    #[Validate('integer|min:0')]
    public int $projects_completed = 0;

    #[Validate('integer|min:0')]
    public int $happy_clients = 0;

    public bool $available_for_work = true;

    public function mount(): void
    {
        $this->profile = Profile::current();
        $this->fill($this->profile->only([
            'name', 'title', 'headline', 'bio', 'about', 'email', 'phone',
            'location', 'resume_url', 'cv_download_label', 'cv_view_label',
            'years_experience', 'projects_completed',
            'happy_clients', 'available_for_work',
        ]));
    }

    public function save(): void
    {
        $this->profile->update($this->validate());

        session()->flash('status', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile-manager');
    }
}
