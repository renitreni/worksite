<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check() && auth('admin')->user()->role === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'body_text' => ['required', 'string'],
            'body_html' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}