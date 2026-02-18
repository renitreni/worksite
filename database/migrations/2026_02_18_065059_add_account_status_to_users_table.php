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
    Schema::table('users', function (Blueprint $table) {
        $table->string('account_status', 20)->default('active')->after('role');
        // optional later:
        // $table->string('status_reason')->nullable()->after('account_status');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // $table->dropColumn(['status_reason']);
        $table->dropColumn(['account_status']);
    });
}

};
