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
        $teachers = \App\Models\Teacher::all();
        $employees = \App\Models\Employee::all();
        
        $types = [
            ['models' => $students, 'type' => \App\Models\Student::class],
            ['models' => $teachers, 'type' => \App\Models\Teacher::class],
            ['models' => $employees, 'type' => \App\Models\Employee::class],
        ];

        foreach ($types as $item) {
            foreach ($item['models'] as $model) {
                // Seed for the last 5 days
                for ($i = 0; $i < 5; $i++) {
                    \App\Models\Attendance::updateOrCreate(
                        [
                            'attendant_id' => $model->id,
                            'attendant_type' => $item['type'],
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
}
