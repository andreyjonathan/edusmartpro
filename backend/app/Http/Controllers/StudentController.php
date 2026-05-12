<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(\App\Services\StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Students/Index', [
            'students' => $this->studentService->getStudents($request->all()),
            'filters' => $request->only(['search', 'status', 'class']),
            'classes' => Student::distinct()->pluck('class'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Students/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:students,nis',
            'name' => 'required|string|max:255',
            'class' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'status' => 'required|in:Active,Inactive',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('message', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required|string|max:255',
            'class' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'status' => 'required|in:Active,Inactive',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('message', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('message', 'Student deleted successfully.');
    }
}
