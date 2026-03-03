<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General
        Setting::set('site.name', 'Workabroad', 'string', 'general');
        Setting::set('site.logo_path', null, 'string', 'general');
        Setting::set('site.support_email', 'support@workabroad.test', 'string', 'general');
        Setting::set('site.timezone', 'Asia/Manila', 'string', 'general');

        // Notifications
        Setting::set('notify.email_enabled', true, 'boolean', 'notifications');
        Setting::set('notify.sms_enabled', false, 'boolean', 'notifications');
        Setting::set('notify.from_email', 'noreply@workabroad.test', 'string', 'notifications');
        Setting::set('notify.from_name', 'Workabroad', 'string', 'notifications');
        Setting::set('notify.admin_alert_email', 'admin@workabroad.test', 'string', 'notifications');
        Setting::set('notify.new_job_post_alert_admin', true, 'boolean', 'notifications');
        Setting::set('notify.new_user_signup_alert_admin', true, 'boolean', 'notifications');

        // Security
        Setting::set('security.force_email_verification', true, 'boolean', 'security');
        Setting::set('security.password_min_length', 8, 'integer', 'security');
        Setting::set('security.login_throttle_per_minute', 10, 'integer', 'security');
        Setting::set('security.session_timeout_minutes', 120, 'integer', 'security');

        // Backup (optional keys for later)
        Setting::set('backup.enabled', true, 'boolean', 'backup');
        Setting::set('backup.retention_days', 14, 'integer', 'backup');
    }
}