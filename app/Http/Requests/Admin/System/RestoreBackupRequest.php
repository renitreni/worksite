<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class RestoreBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route middleware handles permission
    }

    public function rules(): array
    {
        return [
            'backup_run_id' => ['required', 'integer', 'exists:backup_runs,id'],
            'confirm' => ['required', 'accepted'], // simple "I understand" checkbox
        ];
    }

    public function messages(): array
    {
        return [
            'confirm.accepted' => 'Please confirm that you understand this will overwrite the database.',
        ];
    }
}