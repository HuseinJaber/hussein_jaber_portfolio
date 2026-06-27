<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EducationManager;
use App\Livewire\Admin\ExperienceManager;
use App\Livewire\Admin\MessageManager;
use App\Livewire\Admin\ProfileManager;
use App\Livewire\Admin\ProjectManager;
use App\Livewire\Admin\ServiceManager;
use App\Livewire\Admin\SkillManager;
use App\Livewire\Admin\SocialLinkManager;
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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/profile-content', ProfileManager::class)->name('profile');
    Route::get('/projects', ProjectManager::class)->name('projects');
    Route::get('/skills', SkillManager::class)->name('skills');
    Route::get('/services', ServiceManager::class)->name('services');
    Route::get('/experience', ExperienceManager::class)->name('experience');
    Route::get('/education', EducationManager::class)->name('education');
    Route::get('/testimonials', TestimonialManager::class)->name('testimonials');
    Route::get('/socials', SocialLinkManager::class)->name('socials');
    Route::get('/messages', MessageManager::class)->name('messages');
});

// Breeze account/password settings (separate from portfolio content).
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
