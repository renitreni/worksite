<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // standard | gold | platinum (or custom)
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);

            $table->boolean('is_active')->default(true);

            // Optional: display order
            $table->unsignedInteger('sort_order')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
