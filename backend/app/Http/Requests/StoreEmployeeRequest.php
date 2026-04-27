<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'nik' => 'required|string|unique:employees,nik|max:20',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'status' => 'required|in:Active,Inactive',
            'email' => 'nullable|email|unique:employees,email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }
}
