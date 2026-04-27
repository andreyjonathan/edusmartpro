<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = \App\Models\Student::all();
        
        if ($students->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            // Seed for the last 5 days
            for ($i = 0; $i < 5; $i++) {
                \App\Models\Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => now()->subDays($i)->format('Y-m-d')
                    ],
                    [
                        'status' => collect(['Present', 'Present', 'Present', 'Absent', 'Late', 'Sick'])->random(),
                        'notes' => null
                    ]
                );
            }
        }
    }
}
