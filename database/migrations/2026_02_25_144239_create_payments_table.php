<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // employer user id (role: employer)
            $table->foreignId('employer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('employer_subscriptions')
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending|completed|failed
            $table->string('reference')->nullable();
            $table->string('proof_path')->nullable();

            $table->foreignId('verified_by_admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('fail_reason')->nullable();

            $table->timestamps();

            $table->index(['status']);
            $table->index(['employer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};