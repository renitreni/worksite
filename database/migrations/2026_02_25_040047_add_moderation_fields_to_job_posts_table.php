<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // HOLD (TL requirement)
            $table->boolean('is_held')->default(false)->after('status');
            $table->timestamp('held_at')->nullable()->after('is_held');
            $table->text('hold_reason')->nullable()->after('held_at');

            // You said admins are users, so store the acting user id
            $table->unsignedBigInteger('held_by_user_id')->nullable()->after('hold_reason');

            // SOFT-DISABLE (S1)
            $table->boolean('is_disabled')->default(false)->after('held_by_user_id');
            $table->timestamp('disabled_at')->nullable()->after('is_disabled');
            $table->text('disabled_reason')->nullable()->after('disabled_at');
            $table->unsignedBigInteger('disabled_by_user_id')->nullable()->after('disabled_reason');

            // ADMIN NOTES (S2)
            $table->text('admin_notes')->nullable()->after('disabled_by_user_id');
            $table->timestamp('notes_updated_at')->nullable()->after('admin_notes');

            // Helpful index for admin filtering
            $table->index(['status', 'is_held', 'is_disabled'], 'job_posts_admin_filters_idx');
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // Drop index first
            $table->dropIndex('job_posts_admin_filters_idx');

            // Drop columns
            $table->dropColumn([
                'is_held',
                'held_at',
                'hold_reason',
                'held_by_user_id',
                'is_disabled',
                'disabled_at',
                'disabled_reason',
                'disabled_by_user_id',
                'admin_notes',
                'notes_updated_at',
            ]);
        });
    }
};