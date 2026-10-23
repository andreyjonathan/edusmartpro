<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request->user());
        
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $today = Carbon::today();
        $dayOfWeek = $today->locale('id')->isoFormat('dddd'); // Indonesian day name
        
        // Stat cards data
        $classesCount = Schedule::where('teacher_id', $teacher->id)
            ->distinct('class')
            ->count('class');
        
        $studentsCount = Student::whereIn('class', function($q) use ($teacher) {
            $q->select('class')->from('schedules')->where('teacher_id', $teacher->id);
        })->count();

        $todaysSchedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $dayOfWeek)
            ->orderBy('start_time')
            ->get()
            ->map(function ($s) use ($today) {
                return [
                    'id' => $s->id,
                    'subject' => $s->subject?->name ?? $s->subject_id,
                    'classroom' => $s->class,
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'student_count' => Student::where('class', $s->class)->count(),
                ];
            });

        $pendingGrades = Grade::whereHas('subject', function($q) use ($teacher) {
            // Grades don't have teacher_id directly, we need to check via subject or classroom
            // For now, count grades for subjects this teacher teaches
        })->whereNull('final_score')->count();

        // Get announcements
        $announcements = Announcement::with('user')
            ->where(function($q) {
                $q->where('target_role', 'all')
                  ->orWhere('target_role', 'teacher');
            })
            ->where('published_at', '<=', $today)
            ->latest('published_at')
            ->take(5)
            ->get();

        return Inertia::render('Teacher/Dashboard', [
            'stats' => [
                'classes' => $classesCount,
                'students' => $studentsCount,
                'todays_schedule' => $todaysSchedules->count(),
                'pending_grading' => $pendingGrades,
            ],
            'todays_schedules' => $todaysSchedules,
            'announcements' => $announcements,
        ]);
    }

    public function todaySchedule(Request $request)
    {
        $teacher = $this->getTeacher($request->user());
        
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        $today = Carbon::today();
        $dayOfWeek = $today->locale('id')->isoFormat('dddd');
        
        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $dayOfWeek)
            ->orderBy('start_time')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'subject' => $s->subject?->name ?? '',
                    'classroom' => $s->class,
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'student_count' => Student::where('class', $s->class)->count(),
                ];
            });

        return response()->json($schedules);
    }

    public function storeAttendance(Request $request)
    {
        $teacher = $this->getTeacher($request->user());
        
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 403);
        }

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,izin,sakit,alpa',
            'attendances.*.notes' => 'nullable|string',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        
        // Verify teacher owns this schedule
        if ($schedule->teacher_id !== $teacher->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        foreach ($validated['attendances'] as $att) {
            Attendance::updateOrCreate(
                [
                    'attendant_id' => $att['student_id'],
                    'attendant_type' => Student::class,
                    'date' => $validated['date'],
                ],
                [
                    'status' => $att['status'],
                    'notes' => $att['notes'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Absensi berhasil disimpan']);
    }

    public function storeGrade(Request $request)
    {
        $teacher = $this->getTeacher($request->user());
        
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 403);
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'classroom' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'academic_year' => 'required|string',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.task_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.mid_exam_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.final_exam_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.notes' => 'nullable|string',
        ]);

        // Verify teacher teaches this subject/class
        $teaches = Schedule::where('teacher_id', $teacher->id)
            ->where('subject_id', $validated['subject_id'])
            ->where('class', $validated['classroom'])
            ->exists();

        if (!$teaches) {
            return response()->json(['error' => 'Unauthorized for this class/subject'], 403);
        }

        foreach ($validated['grades'] as $g) {
            $task = $g['task_score'] ?? 0;
            $mid = $g['mid_exam_score'] ?? 0;
            $final = $g['final_exam_score'] ?? 0;
            $finalScore = round(($task * 0.30) + ($mid * 0.30) + ($final * 0.40), 2);
            
            $letterGrade = match (true) {
                $finalScore >= 90 => 'A',
                $finalScore >= 80 => 'B',
                $finalScore >= 70 => 'C',
                $finalScore >= 60 => 'D',
                default => 'E',
            };

            Grade::updateOrCreate(
                [
                    'student_id' => $g['student_id'],
                    'subject_id' => $validated['subject_id'],
                    'semester' => $validated['semester'],
                    'academic_year' => $validated['academic_year'],
                ],
                [
                    'task_score' => $task,
                    'mid_exam_score' => $mid,
                    'final_exam_score' => $final,
                    'final_score' => $finalScore,
                    'letter_grade' => $letterGrade,
                    'notes' => $g['notes'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Nilai berhasil disimpan']);
    }

    public function announcements(Request $request)
    {
        $today = Carbon::today();
        $announcements = Announcement::with('user')
            ->where(function($q) {
                $q->where('target_role', 'all')
                  ->orWhere('target_role', 'teacher');
            })
            ->where('published_at', '<=', $today)
            ->latest('published_at')
            ->take(10)
            ->get();

        return response()->json($announcements);
    }

    private function getTeacher($user)
    {
        return Teacher::where('user_id', $user->id)->first();
    }
}