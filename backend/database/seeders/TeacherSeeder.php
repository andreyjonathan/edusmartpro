<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Teacher::factory()->count(10)->create()->each(function ($teacher) {
            if ($teacher->email) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $teacher->email],
                    [
                        'name' => $teacher->name,
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    ]
                );
                $user->assignRole('teacher');
                $teacher->update(['user_id' => $user->id]);
            }
        });
    }
}
