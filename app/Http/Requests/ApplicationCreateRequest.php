<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationCreateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'scholarship_id' => 'required|exists:scholarships,id',
            'personal_statement' => 'required|string|max:5000',
        ];
    }

    public function messages()
    {
        return [
            'scholarship_id.required' => 'Please select a scholarship to apply for.',
            'scholarship_id.exists' => 'The selected scholarship does not exist.',
            'personal_statement.required' => 'Personal statement is required.',
            'personal_statement.max' => 'Personal statement cannot exceed 5000 characters.',
        ];
    }
}