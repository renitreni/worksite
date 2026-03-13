<?php

namespace App\Services\Admin;

use App\Models\Setting;
use App\Models\BackupRun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSystemConfigService
{
    public function getSystemSettings(): array
    {
        $groups = ['general','notifications','security','backups'];

        $settings = Setting::query()
            ->whereIn('group',$groups)
            ->get()
            ->groupBy('group')
            ->map(fn($rows)=>$rows->keyBy('key'));

        $runs = BackupRun::query()
            ->latest()
            ->paginate(15);

        $restoreCandidates = BackupRun::query()
            ->where('status','success')
            ->whereNotNull('file_path')
            ->latest()
            ->limit(50)
            ->get();

        return compact('settings','runs','restoreCandidates');
    }

    public function updateGeneral(array $data): void
    {
        DB::transaction(function() use ($data){

            $adminId = Auth::guard('admin')->id();

            Setting::set('site.name',$data['site']['name'],'string','general',$adminId);
            Setting::set('site.support_email',$data['site']['support_email'],'string','general',$adminId);
            Setting::set('site.timezone',$data['site']['timezone'],'string','general',$adminId);

        });
    }

    public function updateNotifications(array $data): void
    {
        DB::transaction(function() use ($data){

            $adminId = Auth::guard('admin')->id();

            Setting::set('notify.email_enabled',(bool)($data['notify']['email_enabled'] ?? false),'boolean','notifications',$adminId);
            Setting::set('notify.sms_enabled',(bool)($data['notify']['sms_enabled'] ?? false),'boolean','notifications',$adminId);

            Setting::set('notify.from_email',$data['notify']['from_email'],'string','notifications',$adminId);
            Setting::set('notify.from_name',$data['notify']['from_name'],'string','notifications',$adminId);

            Setting::set('notify.admin_alert_email',$data['notify']['admin_alert_email'],'string','notifications',$adminId);

            Setting::set('notify.new_job_post_alert_admin',(bool)($data['notify']['new_job_post_alert_admin'] ?? false),'boolean','notifications',$adminId);
            Setting::set('notify.new_user_signup_alert_admin',(bool)($data['notify']['new_user_signup_alert_admin'] ?? false),'boolean','notifications',$adminId);

        });
    }

    public function updateSecurity(array $data): void
    {
        DB::transaction(function() use ($data){

            $adminId = Auth::guard('admin')->id();

            Setting::set('security.force_email_verification',(bool)($data['security']['force_email_verification'] ?? false),'boolean','security',$adminId);

            Setting::set('security.password_min_length',(int)$data['security']['password_min_length'],'integer','security',$adminId);

            Setting::set('security.login_throttle_per_minute',(int)$data['security']['login_throttle_per_minute'],'integer','security',$adminId);

            Setting::set('security.session_timeout_minutes',(int)$data['security']['session_timeout_minutes'],'integer','security',$adminId);

        });
    }
}