<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Schedules/Index', [
            'schedules' => $this->scheduleService->getSchedules($request->all()),
            'filters' => $request->only(['search', 'day', 'subject_id']),
            'subjects' => Subject::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Schedules/Create', [
            'subjects' => Subject::all(),
            'teachers' => Teacher::where('status', 'Active')->get(),
            'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        $this->scheduleService->createSchedule($request->validated());

        return redirect()->route('schedules.index')->with('message', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return Inertia::render('Schedules/Edit', [
            'schedule' => $schedule,
            'subjects' => Subject::all(),
            'teachers' => Teacher::where('status', 'Active')->get(),
            'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        ]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $this->scheduleService->updateSchedule($schedule, $request->validated());

        return redirect()->route('schedules.index')->with('message', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $this->scheduleService->deleteSchedule($schedule);

        return redirect()->route('schedules.index')->with('message', 'Schedule deleted successfully.');
    }
}
