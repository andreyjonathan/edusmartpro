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
        $query = Attendance::with('attendant');

        if (isset($filters['type'])) {
            $type = $filters['type'];
            $modelClass = match ($type) {
                'student' => \App\Models\Student::class,
                'teacher' => \App\Models\Teacher::class,
                'employee' => \App\Models\Employee::class,
                default => null,
            };
            if ($modelClass) {
                $query->where('attendant_type', $modelClass);
            }
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHasMorph('attendant', '*', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['class'])) {
            $query->whereHasMorph('attendant', [\App\Models\Student::class], function ($q) use ($filters) {
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
            [
                'attendant_id' => $data['attendant_id'],
                'attendant_type' => $data['attendant_type'],
                'date' => $data['date']
            ],
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
