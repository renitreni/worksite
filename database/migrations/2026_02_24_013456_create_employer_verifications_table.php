<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employer_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained('employer_profiles')
                ->cascadeOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');

            $table->text('suspended_reason')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique('employer_profile_id'); // 1 profile = 1 verification row
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_verifications');
    }
};