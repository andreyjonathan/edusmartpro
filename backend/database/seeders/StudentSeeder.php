<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::factory()->count(10)->create()->each(function ($student) {
            // Create User for Parent
            $parentUser = \App\Models\User::create([
                'name' => $student->parent_name,
                'email' => $student->parent_email,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
            $parentUser->assignRole('parent');

            // Create User for Student
            $studentUser = \App\Models\User::create([
                'name' => $student->name,
                'email' => $student->nis . '@student.edusmart.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
            $studentUser->assignRole('student');

            // Update student with user IDs
            $student->update([
                'user_id' => $studentUser->id,
                'parent_user_id' => $parentUser->id,
            ]);
        });
    }
}
