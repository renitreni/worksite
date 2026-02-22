<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_post_id')->constrained('job_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('reason', 100); // e.g. scam, misleading, fake, wrong info
            $table->text('details')->nullable();

            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');

            $table->timestamps();

            $table->index(['job_post_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_reports');
    }
};
