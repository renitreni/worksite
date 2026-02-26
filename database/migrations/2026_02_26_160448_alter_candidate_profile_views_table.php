<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidate_profile_views', function (Blueprint $table) {
            $table->date('view_date')->nullable()->after('candidate_profile_id');
            $table->timestamp('viewed_at')->nullable()->change();

            $table->unique(
                ['employer_profile_id', 'candidate_profile_id', 'view_date'],
                'uniq_emp_cand_day'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
