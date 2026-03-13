<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterEmployerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],

            'company_address' => ['required', 'string', 'max:255'],

            'company_contact' => ['required', 'string', 'max:50'],
            'company_contact_e164' => ['nullable', 'string', 'max:30'],

            'representative_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],

            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }
}