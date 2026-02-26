<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained('employer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('candidate_profile_id')
                ->constrained('candidate_profiles')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['employer_profile_id', 'candidate_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};