<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendant_id' => 'required|integer',
            'attendant_type' => 'required|string|in:App\Models\Student,App\Models\Teacher,App\Models\Employee',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,Sick',
            'notes' => 'nullable|string',
        ];
    }
}
