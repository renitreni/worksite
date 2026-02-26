<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidate_profile_views', function (Blueprint $table) {
            $table->unsignedBigInteger('job_application_id')->after('candidate_profile_id');

            // optional FK (recommended)
            $table->foreign('job_application_id')
                ->references('id')->on('job_applications')
                ->cascadeOnDelete();

            // 1 view per employer per job_application per day
            $table->unique(['employer_profile_id', 'job_application_id', 'view_date'], 'uniq_emp_app_day');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profile_views', function (Blueprint $table) {
            $table->dropUnique('uniq_emp_app_day');
            $table->dropForeign(['job_application_id']);
            $table->dropColumn('job_application_id');
        });
    }
};