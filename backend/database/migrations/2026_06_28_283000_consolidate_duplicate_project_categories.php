<?php

use App\Models\ProjectCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $categories = DB::table('project_categories')->orderBy('sort_order')->get(['id', 'name', 'slug', 'sort_order']);

        /** @var array<string, list<object{id: int, name: string, slug: string, sort_order: int}>> $groups */
        $groups = [];

        foreach ($categories as $category) {
            $canonicalSlug = Str::slug(ProjectCategory::normalizeName($category->name));
            $groups[$canonicalSlug][] = $category;
        }

        foreach ($groups as $canonicalSlug => $members) {
            if (count($members) === 1) {
                $member = $members[0];
                $canonicalName = ProjectCategory::normalizeName($member->name);

                DB::table('project_categories')->where('id', $member->id)->update([
                    'name' => $canonicalName,
                    'slug' => $canonicalSlug,
                    'updated_at' => now(),
                ]);

                continue;
            }

            $canonical = collect($members)->firstWhere('slug', $canonicalSlug)
                ?? collect($members)->sortBy('sort_order')->first();

            $canonicalId = $canonical->id;
            $canonicalName = ProjectCategory::normalizeName($canonical->name);

            DB::table('project_categories')->where('id', $canonicalId)->update([
                'name' => $canonicalName,
                'slug' => $canonicalSlug,
                'updated_at' => now(),
            ]);

            $duplicateIds = collect($members)
                ->where('id', '!=', $canonicalId)
                ->pluck('id');

            foreach ($duplicateIds as $duplicateId) {
                $projectIds = DB::table('project_project_category')
                    ->where('project_category_id', $duplicateId)
                    ->pluck('project_id');

                foreach ($projectIds as $projectId) {
                    $alreadyLinked = DB::table('project_project_category')
                        ->where('project_id', $projectId)
                        ->where('project_category_id', $canonicalId)
                        ->exists();

                    if ($alreadyLinked) {
                        DB::table('project_project_category')
                            ->where('project_id', $projectId)
                            ->where('project_category_id', $duplicateId)
                            ->delete();
                    } else {
                        DB::table('project_project_category')
                            ->where('project_id', $projectId)
                            ->where('project_category_id', $duplicateId)
                            ->update(['project_category_id' => $canonicalId]);
                    }
                }

                DB::table('project_categories')->where('id', $duplicateId)->delete();
            }
        }
    }

    public function down(): void
    {
        // Cannot restore duplicate category variants after merge.
    }
};
