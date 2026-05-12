<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classroom = $this->route('classroom');

        return [
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            'major' => 'nullable|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'academic_year' => 'nullable|string|max:255',
        ];
    }
}
