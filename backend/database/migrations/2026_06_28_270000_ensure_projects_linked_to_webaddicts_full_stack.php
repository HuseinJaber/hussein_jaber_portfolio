<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $experienceId = DB::table('experiences')
            ->where('company', 'TheWebAddicts')
            ->where('role', 'Full Stack Developer')
            ->where('cancelled', false)
            ->value('id');

        if (! $experienceId) {
            return;
        }

        DB::table('projects')->update([
            'experience_id' => $experienceId,
            'work_context' => 'company',
        ]);
    }

    public function down(): void
    {
        // Intentionally no-op: prior link state is not restored.
    }
};
