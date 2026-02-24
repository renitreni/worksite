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

            $table->foreignId('employer_profile_id')
                ->constrained('employer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->nullable()
                ->constrained('subscription_plans')
                ->nullOnDelete();

            // ✅ status (matches your UI filters)
            $table->enum('subscription_status', [
                'inactive',
                'active',
                'expired',
                'canceled'
            ])->default('inactive');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            // ✅ 1 profile = 1 subscription row
            $table->unique('employer_profile_id');

            $table->index(['subscription_status']);
            $table->index(['ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_subscriptions');
    }
};