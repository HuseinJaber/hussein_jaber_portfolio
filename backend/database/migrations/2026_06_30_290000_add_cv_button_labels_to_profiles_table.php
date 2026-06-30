<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('cv_download_label')->nullable()->after('resume_url');
            $table->string('cv_view_label')->nullable()->after('cv_download_label');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['cv_download_label', 'cv_view_label']);
        });
    }
};
