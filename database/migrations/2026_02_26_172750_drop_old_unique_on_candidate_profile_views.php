<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidate_profile_views', function (Blueprint $table) {

            // ✅ remove the OLD constraint (candidate-based)
            $table->dropUnique('uniq_emp_cand_day');

            // (optional) if you want to ensure ONLY application-based unique exists
            // If you already created uniq_emp_app_day before, this will error if duplicated.
            // So only add if you are sure it doesn't exist yet.
            // $table->unique(['employer_profile_id','job_application_id','view_date'], 'uniq_emp_app_day');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profile_views', function (Blueprint $table) {
            // bring back old constraint if rollback
            $table->unique(['employer_profile_id', 'candidate_profile_id', 'view_date'], 'uniq_emp_cand_day');
        });
    }
};