<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_post_id')
                ->constrained('job_posts')
                ->cascadeOnDelete();

            $table->string('event'); 
            // view | apply | shortlist | hired | etc.

            $table->foreignId('candidate_profile_id')
                ->nullable()
                ->constrained('candidate_profiles')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['job_post_id', 'event', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_events');
    }
};