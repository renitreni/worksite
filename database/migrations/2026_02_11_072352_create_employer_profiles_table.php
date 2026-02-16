<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->string('company_address');
            $table->string('company_contact');

            $table->string('representative_name');
            $table->string('position');

            // optional for approval flow
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_profiles');
    }
};
