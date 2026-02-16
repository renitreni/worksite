<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('user_id');

            $table->string('address')->nullable()->after('photo_path');
            $table->date('birth_date')->nullable()->after('address');

            $table->text('bio')->nullable()->after('birth_date');
            $table->unsignedSmallInteger('experience_years')->nullable()->after('bio');

            // Social links
            $table->string('whatsapp')->nullable()->after('experience_years');
            $table->string('facebook')->nullable()->after('whatsapp');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('telegram')->nullable()->after('linkedin');

            // Professional
            $table->string('highest_qualification')->nullable()->after('telegram');
            $table->string('current_salary')->nullable()->after('highest_qualification');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'photo_path',
                'address',
                'birth_date',
                'bio',
                'experience_years',
                'whatsapp',
                'facebook',
                'linkedin',
                'telegram',
                'highest_qualification',
                'current_salary',
            ]);
        });
    }
};
