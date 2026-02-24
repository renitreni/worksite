<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('subscription_plans', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // STANDARD, GOLD, PLATINUM
        $table->string('name');
        $table->unsignedInteger('price'); // 350, 550, 750
        $table->json('features')->nullable(); // limits/flags from plan matrix
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::dropIfExists('subscription_plans');
}
};
