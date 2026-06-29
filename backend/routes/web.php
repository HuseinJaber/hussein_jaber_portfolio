<?php

use App\Livewire\Admin\AnalyticsManager;
use App\Livewire\Admin\CertificationManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EducationManager;
use App\Livewire\Admin\ExperienceManager;
use App\Livewire\Admin\MessageManager;
use App\Livewire\Admin\NewsletterManager;
use App\Livewire\Admin\ProfileManager;
use App\Livewire\Admin\ProjectCategoryManager;
use App\Livewire\Admin\ProjectForm;
use App\Livewire\Admin\ProjectManager;
use App\Livewire\Admin\SectionManager;
use App\Livewire\Admin\ServiceManager;
use App\Livewire\Admin\SkillManager;
use App\Livewire\Admin\SocialLinkManager;
use App\Livewire\Admin\TechStackManager;
use App\Livewire\Admin\TestimonialManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::post('logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// Breeze posts here after login; send admins to the dashboard.
Route::redirect('/dashboard', '/admin')->name('dashboard');

Route::middleware(['auth', 'admin', 'throttle:120,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/profile-content', ProfileManager::class)->name('profile');
    Route::get('/sections', SectionManager::class)->name('sections');
    Route::get('/projects', ProjectManager::class)->name('projects');
    Route::get('/projects/create', ProjectForm::class)->name('projects.create');
    Route::get('/projects/{project}/edit', ProjectForm::class)->name('projects.edit');
    Route::get('/project-categories', ProjectCategoryManager::class)->name('project-categories');
    Route::get('/tech-stacks', TechStackManager::class)->name('tech-stacks');
    Route::get('/skills', SkillManager::class)->name('skills');
    Route::get('/services', ServiceManager::class)->name('services');
    Route::get('/experience', ExperienceManager::class)->name('experience');
    Route::get('/education', EducationManager::class)->name('education');
    Route::get('/certifications', CertificationManager::class)->name('certifications');
    Route::get('/testimonials', TestimonialManager::class)->name('testimonials');
    Route::get('/socials', SocialLinkManager::class)->name('socials');
    Route::get('/messages', MessageManager::class)->name('messages');
    Route::get('/newsletter', NewsletterManager::class)->name('newsletter');
    Route::get('/analytics', AnalyticsManager::class)->name('analytics');
});

// Breeze account/password settings (separate from portfolio content).
Route::view('profile', 'profile')
    ->middleware(['auth', 'admin'])
    ->name('profile');

require __DIR__.'/auth.php';
