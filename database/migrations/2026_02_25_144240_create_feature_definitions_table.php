<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feature_definitions', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique(); // ex: job_limit_active
            $table->string('label');         // ex: Active Job Post Limit
            $table->string('type')->default('boolean'); 
            // boolean | number | select | text | json

            $table->json('options')->nullable();       // for select
            $table->json('default_value')->nullable(); // safe fallback

            $table->boolean('is_core')->default(false);   // protect important keys
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_definitions');
    }
};