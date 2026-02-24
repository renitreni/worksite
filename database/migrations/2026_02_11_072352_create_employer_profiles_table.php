<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employer_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('company_name');
            $table->string('company_address')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('company_website')->nullable();

            $table->text('description')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();

            $table->unsignedBigInteger('total_profile_views')->default(0);

            $table->string('representative_name')->nullable();
            $table->string('position')->nullable();

            $table->timestamps();

            $table->unique('user_id'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_profiles');
    }
};
