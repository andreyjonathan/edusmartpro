<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Services\ClassroomService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    protected $classroomService;

    public function __construct(ClassroomService $classroomService)
    {
        $this->classroomService = $classroomService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Classrooms/Index', [
            'classrooms' => $this->classroomService->getClassrooms($request->all()),
            'filters' => $request->only(['search', 'major', 'academic_year']),
            'majors' => Classroom::distinct()->whereNotNull('major')->pluck('major'),
            'academic_years' => Classroom::distinct()->whereNotNull('academic_year')->pluck('academic_year'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Classrooms/Create', [
            'teachers' => Teacher::where('status', 'Active')->get()
        ]);
    }

    public function store(StoreClassroomRequest $request)
    {
        $this->classroomService->createClassroom($request->validated());

        return redirect()->route('classrooms.index')->with('message', 'Class created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        return Inertia::render('Classrooms/Edit', [
            'classroom' => $classroom,
            'teachers' => Teacher::where('status', 'Active')->get()
        ]);
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $this->classroomService->updateClassroom($classroom, $request->validated());

        return redirect()->route('classrooms.index')->with('message', 'Class updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $this->classroomService->deleteClassroom($classroom);

        return redirect()->route('classrooms.index')->with('message', 'Class deleted successfully.');
    }
}
