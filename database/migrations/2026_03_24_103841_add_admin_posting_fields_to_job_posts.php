<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->foreignId('posted_by_admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('employer_profile_id');
        });
    }

    public function down()
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropForeign(['posted_by_admin_id']);
            $table->dropColumn('posted_by_admin_id');
        });
    }
};
