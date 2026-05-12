<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleService
{
    public function getSchedules(array $filters = []): LengthAwarePaginator
    {
        $query = Schedule::with(['subject', 'teacher']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('class', 'like', "%{$search}%")
                  ->orWhere('day', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('teacher', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($filters['day'])) {
            $query->where('day', $filters['day']);
        }

        if (isset($filters['subject_id']) && $filters['subject_id'] !== '') {
            $query->where('subject_id', $filters['subject_id']);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function createSchedule(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function updateSchedule(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    public function deleteSchedule(Schedule $schedule): ?bool
    {
        return $schedule->delete();
    }
}
