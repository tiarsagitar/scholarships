<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'description' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'receipt.required' => 'Receipt file is required.',
            'receipt.file' => 'Receipt must be a valid file.',
            'receipt.mimes' => 'Receipt must be a JPEG, PNG, JPG, or PDF file.',
            'receipt.max' => 'Receipt file size must not exceed 5MB.',
            'description.max' => 'Description must not exceed 500 characters.',
        ];
    }
}