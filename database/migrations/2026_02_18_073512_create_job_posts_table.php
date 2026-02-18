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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('salary', 12, 2)->nullable();
            $table->string('job_type');
            $table->text('required_skills');

            $table->enum('status', ['open', 'closed'])->default('open');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
