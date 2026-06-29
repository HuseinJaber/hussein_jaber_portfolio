<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'profiles',
        'social_links',
        'skills',
        'services',
        'experiences',
        'education',
        'projects',
        'certifications',
        'testimonials',
        'contact_messages',
        'newsletter_subscribers',
        'analytics_events',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->boolean('cancelled')->default(false)->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('cancelled');
            });
        }
    }
};
