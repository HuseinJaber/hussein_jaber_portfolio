<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tech_stacks', function (Blueprint $table) {
            $table->id();
            $table->boolean('cancelled')->default(false);
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_tech_stack', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tech_stack_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'tech_stack_id']);
        });

        $stackIds = [];
        $sortOrder = 0;

        foreach (DB::table('projects')->whereNotNull('tech_stack')->get(['id', 'tech_stack']) as $project) {
            $names = json_decode($project->tech_stack, true);
            if (! is_array($names)) {
                continue;
            }

            $projectStackIds = [];

            foreach ($names as $name) {
                $name = trim((string) $name);
                if ($name === '') {
                    continue;
                }

                $slug = Str::slug($name);

                if (! isset($stackIds[$slug])) {
                    $stackIds[$slug] = DB::table('tech_stacks')->insertGetId([
                        'name' => $name,
                        'slug' => $slug,
                        'sort_order' => ++$sortOrder,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $projectStackIds[] = $stackIds[$slug];
            }

            foreach (array_unique($projectStackIds) as $techStackId) {
                DB::table('project_tech_stack')->insert([
                    'project_id' => $project->id,
                    'tech_stack_id' => $techStackId,
                ]);
            }
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('tech_stack');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('tech_stack')->nullable()->after('cover_image');
        });

        foreach (DB::table('projects')->pluck('id') as $projectId) {
            $names = DB::table('project_tech_stack')
                ->join('tech_stacks', 'tech_stacks.id', '=', 'project_tech_stack.tech_stack_id')
                ->where('project_tech_stack.project_id', $projectId)
                ->orderBy('tech_stacks.sort_order')
                ->pluck('tech_stacks.name')
                ->all();

            DB::table('projects')->where('id', $projectId)->update([
                'tech_stack' => json_encode($names),
            ]);
        }

        Schema::dropIfExists('project_tech_stack');
        Schema::dropIfExists('tech_stacks');
    }
};
