<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('class', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Students/Index', [
            'students' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search'])
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
