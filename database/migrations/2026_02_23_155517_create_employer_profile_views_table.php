<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('employer_profile_views', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                  ->constrained('employer_profiles')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->date('viewed_on');

            $table->timestamps();

            $table->unique(
                ['employer_profile_id', 'user_id', 'viewed_on'],
                'unique_daily_profile_view'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_profile_views');
    }
};