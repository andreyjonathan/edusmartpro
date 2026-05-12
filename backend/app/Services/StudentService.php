<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentService
{
    public function getStudents(array $filters = []): LengthAwarePaginator
    {
        $query = Student::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['class']) && $filters['class'] !== '') {
            $query->where('class', $filters['class']);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function createStudent(array $data): Student
    {
        return Student::create($data);
    }

    public function updateStudent(Student $student, array $data): bool
    {
        return $student->update($data);
    }

    public function deleteStudent(Student $student): ?bool
    {
        return $student->delete();
    }
}
