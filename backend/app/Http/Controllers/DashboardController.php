<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $stats = [];

        if ($user->hasRole('admin')) {
            $stats = [
                ['name' => 'Total Students', 'value' => \App\Models\Student::count(), 'change' => '+12%', 'trend' => 'up'],
                ['name' => 'Total Teachers', 'value' => \App\Models\Teacher::count(), 'change' => '+3%', 'trend' => 'up'],
                ['name' => 'Total Employees', 'value' => \App\Models\Employee::count(), 'change' => '+2%', 'trend' => 'up'],
                ['name' => 'Total Subjects', 'value' => \App\Models\Subject::count(), 'change' => '+0%', 'trend' => 'neutral'],
            ];
        } elseif ($user->hasRole('teacher')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $stats = [
                ['name' => 'My Classes', 'value' => '4', 'change' => '0', 'trend' => 'neutral'],
                ['name' => 'Students Today', 'value' => '120', 'change' => '+5', 'trend' => 'up'],
                ['name' => 'Attendance Rate', 'value' => '98%', 'change' => '+1%', 'trend' => 'up'],
                ['name' => 'Assignments', 'value' => '12', 'change' => '0', 'trend' => 'neutral'],
            ];
        } elseif ($user->hasRole('parent')) {
            $children = \App\Models\Student::where('parent_user_id', $user->id)->get();
            $stats = [
                ['name' => 'Children Enrolled', 'value' => $children->count(), 'change' => '0', 'trend' => 'neutral'],
                ['name' => 'Attendance Rate', 'value' => '95%', 'change' => '-1%', 'trend' => 'down'],
                ['name' => 'Unpaid Bills', 'value' => '0', 'change' => '0', 'trend' => 'neutral'],
                ['name' => 'Next Exam', 'value' => '2 Days', 'change' => '', 'trend' => 'neutral'],
            ];
        } else { // Student
            $stats = [
                ['name' => 'My Subjects', 'value' => '8', 'change' => '0', 'trend' => 'neutral'],
                ['name' => 'My Attendance', 'value' => '96%', 'change' => '+2%', 'trend' => 'up'],
                ['name' => 'Current Rank', 'value' => '#5', 'change' => '+2', 'trend' => 'up'],
                ['name' => 'Assignments', 'value' => '3', 'change' => '-1', 'trend' => 'down'],
            ];
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}
