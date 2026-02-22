<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {

            // Remove old salary column
            $table->dropColumn('salary');

            // Add new columns
            $table->decimal('salary_min', 12, 2)->nullable()->after('min_experience_years');
            $table->decimal('salary_max', 12, 2)->nullable()->after('salary_min');
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {

            $table->dropColumn(['salary_min', 'salary_max']);

            $table->decimal('salary', 12, 2)->nullable();
        });
    }
};

