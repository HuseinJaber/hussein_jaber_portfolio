<?php

namespace App\Livewire\Admin;

use App\Models\AnalyticsEvent;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => [
                ['label' => 'Page views (7d)', 'value' => AnalyticsEvent::ofType(AnalyticsEvent::TYPE_PAGE_VIEW)->since(now()->subDays(7))->count(), 'route' => 'admin.analytics', 'color' => 'sky'],
                ['label' => 'Newsletter', 'value' => NewsletterSubscriber::active()->count(), 'route' => 'admin.newsletter', 'color' => 'violet'],
                ['label' => 'Projects', 'value' => Project::count(), 'route' => 'admin.projects', 'color' => 'indigo'],
                ['label' => 'Skills', 'value' => Skill::count(), 'route' => 'admin.skills', 'color' => 'emerald'],
                ['label' => 'Services', 'value' => Service::count(), 'route' => 'admin.services', 'color' => 'amber'],
                ['label' => 'Testimonials', 'value' => Testimonial::count(), 'route' => 'admin.testimonials', 'color' => 'rose'],
            ],
            'unread' => ContactMessage::where('is_read', false)->count(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
