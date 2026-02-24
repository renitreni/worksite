<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('company_name');
            $table->string('cover_path')->nullable()->after('logo_path');

            $table->string('company_website')->nullable()->after('company_address');
            $table->text('description')->nullable()->after('company_website');

            // chips/tags like Healthcare, IT, Manufacturing
            $table->json('industries')->nullable()->after('description');

            // ✅ instead of response_time
            $table->unsignedBigInteger('total_profile_views')->default(0)->after('industries');
        });
    }

    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'cover_path',
                'company_website',
                'description',
                'industries',
                'total_profile_views',
            ]);
        });
    }
};
