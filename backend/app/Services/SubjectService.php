<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;

class SubjectService
{
    /**
     * Get a list of subjects with filtering and pagination.
     */
    public function getSubjects(array $filters = []): LengthAwarePaginator
    {
        $query = Subject::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('group', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    /**
     * Create a new subject.
     */
    public function createSubject(array $data): Subject
    {
        return Subject::create($data);
    }

    /**
     * Update an existing subject.
     */
    public function updateSubject(Subject $subject, array $data): bool
    {
        return $subject->update($data);
    }

    /**
     * Delete a subject.
     */
    public function deleteSubject(Subject $subject): ?bool
    {
        return $subject->delete();
    }
}
