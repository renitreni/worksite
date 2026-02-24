<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('employer_subscriptions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('employer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();

        $table->string('status')->default('pending_activation');
        // pending_activation|active|suspended|expired

        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();

        $table->foreignId('activated_by_admin_id')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();
$table->timestamp('activated_at')->nullable();

$table->foreignId('suspended_by_admin_id')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();
$table->timestamp('suspended_at')->nullable();
$table->string('suspend_reason')->nullable();

        $table->timestamps();

        $table->index(['employer_id', 'status']);
        $table->index(['ends_at', 'status']);
    });
}

public function down(): void
{
    Schema::dropIfExists('employer_subscriptions');
}
};
