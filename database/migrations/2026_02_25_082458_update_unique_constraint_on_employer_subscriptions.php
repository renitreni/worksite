<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employer_subscriptions', function (Blueprint $table) {
            // 1️⃣ Drop foreign key constraint
            $table->dropForeign(['employer_profile_id']);

            // 2️⃣ Drop the old unique index
            $table->dropUnique('employer_subscriptions_employer_profile_id_unique');

            // 3️⃣ Add new unique constraint: employer + plan
            $table->unique(['employer_profile_id', 'plan_id']);

            // 4️⃣ Restore foreign key
            $table->foreign('employer_profile_id')
                  ->references('id')
                  ->on('employer_profiles')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employer_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['employer_profile_id']);
            $table->dropUnique(['employer_profile_id', 'plan_id']);
            $table->unique('employer_profile_id');
            $table->foreign('employer_profile_id')
                  ->references('id')
                  ->on('employer_profiles')
                  ->cascadeOnDelete();
        });
    }
};