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
        Schema::create('location_suggestions', function (Blueprint $table) {
            $table->id();

            $table->string('country');
            $table->string('city')->nullable();
            $table->string('area')->nullable();

            $table->unsignedInteger('count')->default(1);
            $table->enum('status', ['pending', 'approved', 'ignored'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_suggestions');
    }
};
