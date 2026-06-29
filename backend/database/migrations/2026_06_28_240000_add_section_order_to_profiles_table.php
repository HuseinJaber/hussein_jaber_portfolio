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
            $table->json('section_order')->nullable()->after('sections');
        });

        $order = json_encode(PortfolioSections::defaultOrder());

        DB::table('profiles')->whereNull('section_order')->update([
            'section_order' => $order,
        ]);
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('section_order');
        });
    }
};
