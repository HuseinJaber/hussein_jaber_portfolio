<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Web');
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('tech_stack')->nullable();
            $table->string('live_url')->nullable();
            $table->string('source_url')->nullable();
            $table->string('client')->nullable();
            $table->year('year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
