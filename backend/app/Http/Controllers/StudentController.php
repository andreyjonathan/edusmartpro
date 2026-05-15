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
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|unique:users,email',
            'parent_phone' => 'nullable|string',
        ]);

        // 1. Create User for Parent
        $parentUser = \App\Models\User::create([
            'name' => $validated['parent_name'],
            'email' => $validated['parent_email'],
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $parentUser->assignRole('parent');

        // 2. Create User for Student (Optional but good for system access)
        $studentUser = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['nis'] . '@student.edusmart.com', // Unique placeholder email
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $studentUser->assignRole('student');

        // 3. Create Student and link to Users
        $studentData = array_merge($validated, [
            'user_id' => $studentUser->id,
            'parent_user_id' => $parentUser->id,
        ]);

        Student::create($studentData);

        return redirect()->route('students.index')->with('message', 'Student and Parent account created successfully.');
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
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|unique:users,email,' . ($student->parent_user_id ?? 0),
            'parent_phone' => 'nullable|string',
        ]);

        // Sync Student User
        if ($student->user_id) {
            \App\Models\User::where('id', $student->user_id)->update([
                'name' => $validated['name'],
            ]);
        }

        // Sync Parent User
        if ($student->parent_user_id) {
            \App\Models\User::where('id', $student->parent_user_id)->update([
                'name' => $validated['parent_name'],
                'email' => $validated['parent_email'],
            ]);
        }

        $student->update($validated);

        return redirect()->route('students.index')->with('message', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('message', 'Student deleted successfully.');
    }
}
