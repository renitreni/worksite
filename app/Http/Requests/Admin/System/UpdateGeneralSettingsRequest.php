<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth handled by route middleware
    }

    public function rules(): array
    {
        return [
            'site.name' => ['required', 'string', 'max:100'],
            'site.support_email' => ['required', 'email', 'max:255'],
            'site.timezone' => ['required', 'string', 'max:100'],
            // logo handled later
            // 'site.logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}