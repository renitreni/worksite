<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('education_level', 30)
                ->nullable()
                ->after('min_experience_years');
            // example values: high_school, college, masteral, phd
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn('education_level');
        });
    }
};
