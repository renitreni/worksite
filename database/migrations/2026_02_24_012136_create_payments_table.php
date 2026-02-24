<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();

        $table->unsignedInteger('amount');
        $table->string('status')->default('pending'); // pending|completed|failed

        // Optional proof/reference (useful if you do manual verification)
        $table->string('reference')->nullable();
        $table->string('proof_path')->nullable();

        // Admin verification audit fields (admin verifies)
        $table->foreignId('verified_by_admin_id')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();
        $table->timestamp('verified_at')->nullable();

        // For failed payments
        $table->string('fail_reason')->nullable();

        $table->timestamps();

        $table->index(['status', 'created_at']);
        $table->index(['employer_id', 'created_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
