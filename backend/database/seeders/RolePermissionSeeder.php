<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Student Management
            'view students',
            'create students',
            'edit students',
            'delete students',

            // Teacher Management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',

            // Academic
            'view subjects',
            'manage subjects',
            'view classrooms',
            'manage classrooms',
            'view schedules',
            'manage schedules',

            // Attendance
            'view attendance',
            'mark attendance',
            'manage attendance',

            // Financial (SPP)
            'view payments',
            'manage payments',

            // Settings
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // Admin: All permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Teacher: Academic and Attendance
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view students',
            'view subjects',
            'view classrooms',
            'view schedules',
            'view attendance',
            'mark attendance',
        ]);

        // Student: View their own data
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view subjects',
            'view classrooms',
            'view schedules',
            'view attendance',
        ]);

        // Parent: View their child's data
        $parentRole = Role::firstOrCreate(['name' => 'parent']);
        $parentRole->givePermissionTo([
            'view students',
            'view subjects',
            'view classrooms',
            'view schedules',
            'view attendance',
            'view payments',
        ]);
    }
}
