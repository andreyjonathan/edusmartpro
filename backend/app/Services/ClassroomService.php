<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassroomService
{
    public function getClassrooms(array $filters = []): LengthAwarePaginator
    {
        $query = Classroom::with('homeroomTeacher');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhereHas('homeroomTeacher', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($filters['major']) && $filters['major'] !== '') {
            $query->where('major', $filters['major']);
        }

        if (isset($filters['academic_year']) && $filters['academic_year'] !== '') {
            $query->where('academic_year', $filters['academic_year']);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function createClassroom(array $data): Classroom
    {
        return Classroom::create($data);
    }

    public function updateClassroom(Classroom $classroom, array $data): bool
    {
        return $classroom->update($data);
    }

    public function deleteClassroom(Classroom $classroom): ?bool
    {
        return $classroom->delete();
    }
}
