<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->foreignId('industry_id')
                ->nullable()
                ->after('description') // or after company_website if you prefer
                ->constrained('industries')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('industry_id');
        });
    }
};
