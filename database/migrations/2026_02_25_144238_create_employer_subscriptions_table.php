<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employer_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained('employer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->string('subscription_status')->default('inactive');
            // inactive | active | expired | canceled

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            // ✅ Short custom index names
            $table->index(
                ['employer_profile_id', 'subscription_status'],
                'emp_sub_profile_status_idx'
            );

            $table->index('ends_at', 'emp_sub_ends_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_subscriptions');
    }
};
