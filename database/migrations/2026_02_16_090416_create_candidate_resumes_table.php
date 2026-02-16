<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidate_resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Resume/CV file (single)
            $table->string('resume_path')->nullable();
            $table->string('resume_original_name')->nullable();
            $table->string('resume_mime')->nullable();
            $table->unsignedBigInteger('resume_size')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_resumes');
    }
};
