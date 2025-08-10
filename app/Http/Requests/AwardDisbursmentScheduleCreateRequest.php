<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwardDisbursmentScheduleCreateRequest extends FormRequest
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
            'schedules' => 'required|array|min:1',
            'schedules.*.award_allocation_id' => 'required|exists:award_allocations,id',
            'schedules.*.amount' => 'required|numeric|min:0.01',
            'schedules.*.scheduled_date' => 'required|date|after:today',
            'schedules.*.description' => 'nullable|string|max:255',
        ];
    }
}
