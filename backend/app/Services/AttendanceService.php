<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceService
{
    /**
     * Get a list of attendances with filtering and pagination.
     */
    public function getAttendances(array $filters = []): LengthAwarePaginator
    {
        $query = Attendance::with('student');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['class'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('class', $filters['class']);
            });
        }

        return $query->latest('date')->paginate(10)->withQueryString();
    }

    /**
     * Create a new attendance record.
     */
    public function createAttendance(array $data): Attendance
    {
        return Attendance::updateOrCreate(
            ['student_id' => $data['student_id'], 'date' => $data['date']],
            ['status' => $data['status'], 'notes' => $data['notes'] ?? null]
        );
    }

    /**
     * Update an existing attendance record.
     */
    public function updateAttendance(Attendance $attendance, array $data): bool
    {
        return $attendance->update($data);
    }

    /**
     * Delete an attendance record.
     */
    public function deleteAttendance(Attendance $attendance): ?bool
    {
        return $attendance->delete();
    }
}
