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
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->boolean('cancelled')->default(false);
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $defaults = ['Web', 'E-Commerce', 'WordPress', 'Laravel', 'React', 'Mobile', 'API'];
        $categoryIds = [];

        foreach ($defaults as $i => $name) {
            $id = DB::table('project_categories')->insertGetId([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $categoryIds[$name] = $id;
        }

        $existing = DB::table('projects')->select('category')->distinct()->pluck('category');
        foreach ($existing as $name) {
            if ($name && ! isset($categoryIds[$name])) {
                $categoryIds[$name] = DB::table('project_categories')->insertGetId([
                    'name' => $name,
                    'slug' => Str::slug($name).'-'.Str::random(4),
                    'sort_order' => count($categoryIds) + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('slug')->constrained('project_categories');
        });

        foreach (DB::table('projects')->get(['id', 'category']) as $project) {
            $name = $project->category ?: 'Web';
            if (! isset($categoryIds[$name])) {
                $categoryIds[$name] = DB::table('project_categories')->insertGetId([
                    'name' => $name,
                    'slug' => Str::slug($name).'-'.Str::random(4),
                    'sort_order' => count($categoryIds) + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('projects')->where('id', $project->id)->update([
                'category_id' => $categoryIds[$name],
            ]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('category')->default('Web')->after('slug');
        });

        foreach (DB::table('projects')->get(['id', 'category_id']) as $project) {
            $name = DB::table('project_categories')->where('id', $project->category_id)->value('name') ?? 'Web';
            DB::table('projects')->where('id', $project->id)->update(['category' => $name]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('project_categories');
    }
};
