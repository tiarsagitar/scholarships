<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwardCreateRequest extends FormRequest
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
            'allocations' => 'required|array|min:1',
            'allocations.*.cost_category_id' => 'required|exists:cost_categories,id',
            'allocations.*.amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
