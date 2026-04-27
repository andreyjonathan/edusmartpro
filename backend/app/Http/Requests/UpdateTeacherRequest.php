<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
        $teacher = $this->route('teacher');

        return [
            'nip' => 'required|string|unique:teachers,nip,' . $teacher->id . '|max:20',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'status' => 'required|in:Active,Inactive',
            'email' => 'nullable|email|unique:teachers,email,' . $teacher->id . '|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }
}
