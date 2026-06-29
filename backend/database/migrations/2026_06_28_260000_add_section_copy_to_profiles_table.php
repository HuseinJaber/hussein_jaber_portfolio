<?php

use App\Support\PortfolioSections;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->json('section_copy')->nullable()->after('section_order');
        });

        DB::table('profiles')->whereNull('section_copy')->update([
            'section_copy' => json_encode(PortfolioSections::defaultCopy()),
        ]);
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('section_copy');
        });
    }
};
