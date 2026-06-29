<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\TechStack;
use App\Services\DocumentProjectScanner;
use Illuminate\Database\Seeder;

class DocumentProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $webAddictsId = Experience::webAddicts()?->id;
        $experienceLink = $webAddictsId
            ? ['work_context' => 'company', 'experience_id' => $webAddictsId]
            : ['work_context' => 'none', 'experience_id' => null];

        $scanner = new DocumentProjectScanner;
        $projects = $scanner->scan();

        $slugs = [];

        foreach ($projects as $project) {
            unset($project['local_path'], $project['folder']);
            $categoryName = $project['category'];
            $techNames = $project['tech_stack'] ?? [];
            unset($project['category'], $project['tech_stack']);

            $categoryIds = ProjectCategory::idsForNames([$categoryName]);
            $techStackIds = collect($techNames)
                ->map(fn (string $name) => TechStack::idForName($name))
                ->unique()
                ->values()
                ->all();

            $project = array_merge($project, $experienceLink);
            $slugs[] = $project['slug'];

            $record = Project::updateOrCreate(['slug' => $project['slug']], $project);
            $record->projectCategories()->sync($categoryIds);
            $record->techStacks()->sync($techStackIds);
        }

        $midis = $this->syncMidisAggregate($experienceLink);
        $slugs[] = $midis['slug'];

        Project::whereNotIn('slug', $slugs)->delete();

        $publishedCount = Project::published()->count();
        $developmentCount = Project::published()->where('engagement_type', 'development')->count();
        $supportCount = Project::published()->where('engagement_type', 'support')->count();

        Profile::query()->where('id', 1)->update([
            'projects_completed' => $publishedCount,
        ]);

        $this->command?->info(sprintf(
            'Imported %d local projects + Midis aggregate · %d published (%d development, %d support)',
            count($projects),
            $publishedCount,
            $developmentCount,
            $supportCount,
        ));
    }

    /**
     * @param  array{work_context: string, experience_id: int|null}  $experienceLink
     * @return array{slug: string}
     */
    private function syncMidisAggregate(array $experienceLink): array
    {
        $config = config('portfolio_engagement.midis', []);
        $sitesCount = (int) ($config['sites_count'] ?? 79);
        $client = (string) ($config['client'] ?? 'Midis Group');
        $slug = 'midis-group-wordpress-support';

        $record = Project::updateOrCreate(
            ['slug' => $slug],
            array_merge([
                'title' => 'Midis Group — WordPress Support',
                'engagement_type' => 'support',
                'contribution_areas' => ['cms', 'frontend'],
                'short_description' => "Ongoing WordPress support and maintenance across {$sitesCount}+ client websites on Midis server infrastructure.",
                'description' => "Long-term WordPress support role for {$client}: updates, bug fixes, plugin and theme maintenance, content changes, performance tuning and deployment support across {$sitesCount}+ live websites hosted on the Midis server environment.\n\nIndividual client sites are maintained under Midis Group — this portfolio entry represents the full scope of that support work rather than listing each site separately.",
                'client' => $client,
                'year' => 2024,
                'sites_count' => $sitesCount,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 0,
            ], $experienceLink)
        );

        $record->projectCategories()->sync([ProjectCategory::idForName('WordPress')]);
        $record->techStacks()->sync(TechStack::idsForNames([
            'WordPress', 'PHP', 'MySQL', 'HTML', 'CSS', 'JavaScript',
        ]));

        return ['slug' => $slug];
    }
}
