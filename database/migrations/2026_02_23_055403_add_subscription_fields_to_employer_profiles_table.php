<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            // Subscription
            $table->string('plan')->nullable()->after('status'); // standard|gold|platinum
            $table->string('subscription_status')->nullable()->after('plan'); // active|expired|suspended|none

            // dates (use date or timestamp; your table uses timestamps for approved_at/rejected_at)
            $table->timestamp('starts_at')->nullable()->after('subscription_status');
            $table->timestamp('ends_at')->nullable()->after('starts_at');

            $table->text('suspended_reason')->nullable()->after('ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'subscription_status',
                'starts_at',
                'ends_at',
                'suspended_reason',
            ]);
        });
    }
};