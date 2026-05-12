<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherService
{
    /**
     * Get a list of teachers with filtering and pagination.
     */
    public function getTeachers(array $filters = []): LengthAwarePaginator
    {
        $query = Teacher::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['subject']) && $filters['subject'] !== '') {
            $query->where('subject', $filters['subject']);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    /**
     * Create a new teacher.
     */
    public function createTeacher(array $data): Teacher
    {
        return Teacher::create($data);
    }

    /**
     * Update an existing teacher.
     */
    public function updateTeacher(Teacher $teacher, array $data): bool
    {
        return $teacher->update($data);
    }

    /**
     * Delete a teacher.
     */
    public function deleteTeacher(Teacher $teacher): ?bool
    {
        return $teacher->delete();
    }
}
