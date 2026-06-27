<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->longText('about')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->string('resume_url')->nullable();
            $table->unsignedInteger('years_experience')->default(0);
            $table->unsignedInteger('projects_completed')->default(0);
            $table->unsignedInteger('happy_clients')->default(0);
            $table->boolean('available_for_work')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
