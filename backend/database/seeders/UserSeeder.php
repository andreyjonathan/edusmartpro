<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@edusmart.com'],
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');
    }
}
