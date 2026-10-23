<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    /**
     * Hitung nilai akhir: 30% tugas + 30% UTS + 40% UAS
     */
    private function calculateFinalScore(float $task, float $mid, float $final): float
    {
        return round(($task * 0.30) + ($mid * 0.30) + ($final * 0.40), 2);
    }

    private function letterGrade(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'E',
        };
    }

    public function index(Request $request)
    {
        $query = Grade::with(['student', 'subject'])
            ->when($request->search, function ($q, $search) {
                $q->whereHas('student', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            })
            ->when($request->subject_id, fn ($q, $id) => $q->where('subject_id', $id))
            ->when($request->semester, fn ($q, $sem) => $q->where('semester', $sem));

        return Inertia::render('Grades/Index', [
            'grades' => $query->latest()->paginate(15)->withQueryString(),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['search', 'subject_id', 'semester']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Grades/Create', [
            'students' => Student::orderBy('name')->get(['id', 'name', 'nis']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateGrade($request);

        $final = $this->calculateFinalScore(
            $validated['task_score'],
            $validated['mid_exam_score'],
            $validated['final_exam_score']
        );

        Grade::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'semester' => $validated['semester'],
                'academic_year' => $validated['academic_year'],
            ],
            $validated + [
                'final_score' => $final,
                'letter_grade' => $this->letterGrade($final),
            ]
        );

        return redirect()->route('grades.index')->with('message', 'Grade saved successfully.');
    }

    public function edit(Grade $grade)
    {
        return Inertia::render('Grades/Edit', [
            'grade' => $grade->load(['student', 'subject']),
            'students' => Student::orderBy('name')->get(['id', 'name', 'nis']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $this->validateGrade($request);

        $final = $this->calculateFinalScore(
            $validated['task_score'],
            $validated['mid_exam_score'],
            $validated['final_exam_score']
        );

        $grade->update($validated + [
            'final_score' => $final,
            'letter_grade' => $this->letterGrade($final),
        ]);

        return redirect()->route('grades.index')->with('message', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')->with('message', 'Grade deleted successfully.');
    }

    private function validateGrade(Request $request): array
    {
        return $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'task_score' => 'required|numeric|min:0|max:100',
            'mid_exam_score' => 'required|numeric|min:0|max:100',
            'final_exam_score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);
    }
}
