<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:user_activity,job_postings,revenue,applications_hires,applications_detailed'
            ],
            'date_from' => [
                'required',
                'date'
            ],
            'date_to' => [
                'required',
                'date',
                'after_or_equal:date_from'
            ],
        ];
    }
}