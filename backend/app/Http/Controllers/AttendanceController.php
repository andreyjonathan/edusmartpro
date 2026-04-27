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
        return Inertia::render('Attendances/Index', [
            'attendances' => $this->attendanceService->getAttendances($request->all()),
            'filters' => $request->only(['search', 'date', 'status', 'class']),
            'classes' => Student::distinct()->pluck('class')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Attendances/Create', [
            'students' => Student::select('id', 'name', 'nis')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $this->attendanceService->createAttendance($request->validated());

        return redirect()->route('attendances.index')->with('message', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return Inertia::render('Attendances/Show', [
            'attendance' => $attendance->load('student')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        return Inertia::render('Attendances/Edit', [
            'attendance' => $attendance->load('student'),
            'students' => Student::select('id', 'name', 'nis')->get()
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
