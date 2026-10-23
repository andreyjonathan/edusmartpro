<?php

namespace App\Http\Controllers;

use App\Models\LessonLog;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonLogController extends Controller
{
    public function index(Request $request)
    {
        $query = LessonLog::with(['teacher', 'subject'])
            ->when($request->search, function ($q, $search) {
                $q->where('topic', 'like', "%{$search}%")
                    ->orWhere('class_name', 'like', "%{$search}%");
            })
            ->when($request->teacher_id, fn ($q, $id) => $q->where('teacher_id', $id));

        return Inertia::render('LessonLogs/Index', [
            'lessonLogs' => $query->latest('date')->paginate(15)->withQueryString(),
            'teachers' => Teacher::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['search', 'teacher_id']),
        ]);
    }

    public function create()
    {
        return Inertia::render('LessonLogs/Create', [
            'teachers' => Teacher::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        LessonLog::create($this->validateLog($request));

        return redirect()->route('lesson-logs.index')->with('message', 'Lesson log created successfully.');
    }

    public function edit(LessonLog $lessonLog)
    {
        return Inertia::render('LessonLogs/Edit', [
            'lessonLog' => $lessonLog,
            'teachers' => Teacher::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, LessonLog $lessonLog)
    {
        $lessonLog->update($this->validateLog($request));

        return redirect()->route('lesson-logs.index')->with('message', 'Lesson log updated successfully.');
    }

    public function destroy(LessonLog $lessonLog)
    {
        $lessonLog->delete();

        return redirect()->route('lesson-logs.index')->with('message', 'Lesson log deleted successfully.');
    }

    private function validateLog(Request $request): array
    {
        return $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_name' => 'required|string|max:100',
            'date' => 'required|date',
            'topic' => 'required|string|max:500',
            'notes' => 'nullable|string',
        ]);
    }
}
