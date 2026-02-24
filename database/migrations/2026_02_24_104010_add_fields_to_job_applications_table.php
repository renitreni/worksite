<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('candidate_id');
            $table->string('email')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('email');

            $table->string('cover_letter_file_path')->nullable()->after('cover_letter');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['full_name','email','phone','cover_letter_file_path']);
        });
    }
};