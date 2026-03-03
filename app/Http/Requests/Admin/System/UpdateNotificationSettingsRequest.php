<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notify.email_enabled' => ['nullable', 'boolean'],
            'notify.sms_enabled' => ['nullable', 'boolean'],

            'notify.from_email' => ['required', 'email', 'max:255'],
            'notify.from_name' => ['required', 'string', 'max:100'],

            'notify.admin_alert_email' => ['required', 'email', 'max:255'],

            'notify.new_job_post_alert_admin' => ['nullable', 'boolean'],
            'notify.new_user_signup_alert_admin' => ['nullable', 'boolean'],
        ];
    }
}