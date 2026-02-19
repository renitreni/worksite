<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            // Basic Job Info
            $table->string('title');
            $table->string('industry');   // dropdown from admin
            $table->text('skills')->nullable(); // dropdown (can store multiple as CSV)

            // Location (all dropdown from admin)
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('area')->nullable();

            // Experience
            $table->unsignedTinyInteger('min_experience_years')->nullable();

            // Salary
            $table->decimal('salary', 12, 2)->nullable();
            $table->string('salary_currency', 10)->default('PHP');

            // Gender + Age
            $table->enum('gender', ['male', 'female', 'both'])->default('both');
            $table->unsignedTinyInteger('age_min')->nullable();
            $table->unsignedTinyInteger('age_max')->nullable();

            // Dates
            $table->timestamp('posted_at')->nullable();
            $table->date('apply_until')->nullable();

            // Job Details
            $table->longText('job_description')->nullable();
            $table->longText('job_qualifications')->nullable();
            $table->longText('additional_information')->nullable();

            // Principal / Employer Details
            $table->string('principal_employer')->nullable();
            $table->string('dmw_registration_no')->nullable();
            $table->string('principal_employer_address')->nullable();

            // Placement Fee
            $table->decimal('placement_fee', 12, 2)->nullable();
            $table->string('placement_fee_currency', 10)->default('PHP');

            // Status
            $table->enum('status', ['open', 'closed'])->default('open');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
