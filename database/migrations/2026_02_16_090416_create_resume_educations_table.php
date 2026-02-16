<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resume_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('candidate_resumes')->cascadeOnDelete();

            $table->string('degree');
            $table->string('school');
            $table->string('year')->nullable(); // "2020 - 2024"
            $table->text('notes')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_educations');
    }
};
