<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_project_category', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'project_category_id']);
        });

        foreach (DB::table('projects')->whereNotNull('category_id')->get(['id', 'category_id']) as $project) {
            DB::table('project_project_category')->insert([
                'project_id' => $project->id,
                'project_category_id' => $project->category_id,
            ]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('slug')->constrained('project_categories');
        });

        foreach (DB::table('project_project_category')->get() as $row) {
            DB::table('projects')->where('id', $row->project_id)->update([
                'category_id' => $row->project_category_id,
            ]);
        }

        Schema::dropIfExists('project_project_category');
    }
};
