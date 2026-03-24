<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_management_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('message')->nullable();

            $table->enum('status', ['pending', 'approved', 'declined'])
                ->default('pending');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_management_requests');
    }
};
