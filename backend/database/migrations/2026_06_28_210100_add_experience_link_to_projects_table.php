<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('experience_id')->nullable()->after('client')->constrained('experiences')->nullOnDelete();
            $table->string('work_context', 20)->default('none')->after('experience_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('experience_id');
            $table->dropColumn('work_context');
        });
    }
};
