<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;

class PortfolioController extends Controller
{
    /**
     * Aggregate endpoint: everything the public site needs in one request.
     */
    public function index()
    {
        return response()->json([
            'profile' => Profile::current(),
            'socials' => SocialLink::active()->orderBy('sort_order')->get(),
            'skills' => Skill::active()->orderBy('sort_order')->get(),
            'services' => Service::active()->orderBy('sort_order')->get(),
            'experiences' => Experience::notCancelled()->orderBy('sort_order')->withCount(['projects' => fn ($q) => $q->published()])->get(),
            'education' => Education::notCancelled()->orderBy('sort_order')->get(),
            'certifications' => Certification::published()->orderBy('sort_order')->get(),
            'projects' => Project::published()
                ->with(['experience:id,company,role', 'projectCategories:id,name', 'techStacks:id,name'])
                ->orderBy('sort_order')
                ->get(),
            'testimonials' => Testimonial::published()->orderBy('sort_order')->get(),
        ]);
    }

    public function projects()
    {
        return response()->json(
            Project::published()
                ->with(['experience:id,company,role', 'projectCategories:id,name', 'techStacks:id,name'])
                ->orderBy('sort_order')
                ->get()
        );
    }

    public function project(string $slug)
    {
        $project = Project::published()
            ->with(['experience:id,company,role', 'projectCategories:id,name', 'techStacks:id,name'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($project);
    }
}
