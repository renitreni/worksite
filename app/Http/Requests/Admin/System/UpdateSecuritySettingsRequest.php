<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSecuritySettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'security.force_email_verification' => ['nullable', 'boolean'],

            'security.password_min_length' => ['required', 'integer', 'min:6', 'max:64'],
            'security.login_throttle_per_minute' => ['required', 'integer', 'min:1', 'max:120'],
            'security.session_timeout_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
        ];
    }
}