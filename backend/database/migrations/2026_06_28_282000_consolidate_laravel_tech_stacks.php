<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $laravelStacks = DB::table('tech_stacks')
            ->where(function ($query) {
                $query->where('name', 'like', 'Laravel%')
                    ->orWhere('slug', 'like', 'laravel%');
            })
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'sort_order']);

        if ($laravelStacks->isEmpty()) {
            return;
        }

        $canonical = $laravelStacks->firstWhere('slug', 'laravel') ?? $laravelStacks->first();
        $canonicalId = $canonical->id;

        DB::table('tech_stacks')->where('id', $canonicalId)->update([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'updated_at' => now(),
        ]);

        $versionIds = $laravelStacks
            ->where('id', '!=', $canonicalId)
            ->pluck('id');

        foreach ($versionIds as $versionId) {
            $projectIds = DB::table('project_tech_stack')
                ->where('tech_stack_id', $versionId)
                ->pluck('project_id');

            foreach ($projectIds as $projectId) {
                $alreadyLinked = DB::table('project_tech_stack')
                    ->where('project_id', $projectId)
                    ->where('tech_stack_id', $canonicalId)
                    ->exists();

                if ($alreadyLinked) {
                    DB::table('project_tech_stack')
                        ->where('project_id', $projectId)
                        ->where('tech_stack_id', $versionId)
                        ->delete();
                } else {
                    DB::table('project_tech_stack')
                        ->where('project_id', $projectId)
                        ->where('tech_stack_id', $versionId)
                        ->update(['tech_stack_id' => $canonicalId]);
                }
            }

            DB::table('tech_stacks')->where('id', $versionId)->delete();
        }
    }

    public function down(): void
    {
        // Cannot restore version-specific Laravel stacks after merge.
    }
};
