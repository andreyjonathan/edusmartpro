<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Services\AttendanceService;
use App\Models\Student;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'student');
        
        return Inertia::render('Attendances/Index', [
            'attendances' => $this->attendanceService->getAttendances($request->all()),
            'filters' => $request->only(['search', 'date', 'status', 'class', 'type']),
            'classes' => \App\Models\Student::distinct()->pluck('class'),
            'type' => $type
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'student');
        
        $attendants = match($type) {
            'teacher' => \App\Models\Teacher::where('status', 'Active')->select('id', 'name')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name, 'type' => \App\Models\Teacher::class]),
            'employee' => \App\Models\Employee::where('status', 'Active')->select('id', 'name')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name, 'type' => \App\Models\Employee::class]),
            default => \App\Models\Student::where('status', 'Active')->select('id', 'name', 'nis')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name . ' (' . $item->nis . ')', 'type' => \App\Models\Student::class]),
        };

        return Inertia::render('Attendances/Create', [
            'attendants' => $attendants,
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $this->attendanceService->createAttendance($request->validated());

        return redirect()->route('attendances.index', ['type' => $request->get('type', 'student')])->with('message', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return Inertia::render('Attendances/Show', [
            'attendance' => $attendance->load('attendant')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load('attendant');
        $type = 'student';
        if ($attendance->attendant_type === \App\Models\Teacher::class) $type = 'teacher';
        if ($attendance->attendant_type === \App\Models\Employee::class) $type = 'employee';

        $attendants = match($type) {
            'teacher' => \App\Models\Teacher::where('status', 'Active')->select('id', 'name')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name, 'type' => \App\Models\Teacher::class]),
            'employee' => \App\Models\Employee::where('status', 'Active')->select('id', 'name')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name, 'type' => \App\Models\Employee::class]),
            default => \App\Models\Student::where('status', 'Active')->select('id', 'name', 'nis')->get()->map(fn($item) => ['id' => $item->id, 'name' => $item->name . ' (' . $item->nis . ')', 'type' => \App\Models\Student::class]),
        };

        return Inertia::render('Attendances/Edit', [
            'attendance' => $attendance,
            'attendants' => $attendants,
            'type' => $type
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $this->attendanceService->updateAttendance($attendance, $request->validated());

        return redirect()->route('attendances.index')->with('message', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->attendanceService->deleteAttendance($attendance);

        return redirect()->route('attendances.index')->with('message', 'Attendance record deleted successfully.');
    }
}
