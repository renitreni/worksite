<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->boolean('allow_admin_management')->default(false)->after('position');
        });
    }

    public function down()
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn('allow_admin_management');
        });
    }
};
