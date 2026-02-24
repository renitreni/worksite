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

            $table->string('plan')->nullable(); // e.g. basic, premium, etc.
            $table->enum('subscription_status', ['inactive', 'active', 'expired', 'canceled'])->default('inactive');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            $table->unique('employer_profile_id'); // 1 profile = 1 subscription row
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_subscriptions');
    }
};