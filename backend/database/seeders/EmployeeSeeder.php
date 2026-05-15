<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Employee::factory()->count(10)->create()->each(function ($employee) {
            if ($employee->email) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $employee->email],
                    [
                        'name' => $employee->name,
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    ]
                );
                // Assign a generic role or just leave as is. 
                // For now, I'll assign 'admin' if they are HR/Manager, otherwise leave empty or create 'staff' role.
                // To keep it simple, I'll just assign no specific role for employees yet, or I can add 'staff'.
            }
        });
    }
}
