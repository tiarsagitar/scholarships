<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUploadRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'documents' => 'required|array',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'documents.required' => 'Please select at least one document to upload.',
            'documents.array' => 'Documents must be provided as an array.',
            'documents.*.required' => 'Each document is required.',
            'documents.*.file' => 'Each document must be a valid file.',
            'documents.*.mimes' => 'Documents must be PDF, DOC, DOCX, JPG, JPEG, or PNG files.',
            'documents.*.max' => 'Each document must not exceed 10MB.',
            'names.required' => 'Document names are required.',
            'names.array' => 'Document names must be provided as an array.',
            'names.*.required' => 'Each document must have a name.',
            'names.*.max' => 'Document name cannot exceed 255 characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('documents') && $this->has('names')) {
                $documentsCount = is_array($this->documents) ? count($this->documents) : 0;
                $namesCount = is_array($this->names) ? count($this->names) : 0;
                
                if ($documentsCount !== $namesCount) {
                    $validator->errors()->add('documents', 'Number of documents must match number of names.');
                }
            }
        });
    }
}