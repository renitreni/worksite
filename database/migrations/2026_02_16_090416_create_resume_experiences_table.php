<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resume_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('candidate_resumes')->cascadeOnDelete();

            $table->string('role');
            $table->string('company');
            $table->string('start')->nullable(); // keep text like "Jan 2023"
            $table->string('end')->nullable();   // "Present" etc.
            $table->text('description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_experiences');
    }
};
