<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:classrooms,name|max:255',
            'major' => 'nullable|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'academic_year' => 'nullable|string|max:255',
        ];
    }
}
