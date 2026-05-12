<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // School Profile
            ['key' => 'school_name', 'value' => 'SMA EduSmart Pro', 'group' => 'school', 'type' => 'string'],
            ['key' => 'school_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta', 'group' => 'school', 'type' => 'string'],
            ['key' => 'school_phone', 'value' => '(021) 1234567', 'group' => 'school', 'type' => 'string'],
            ['key' => 'school_email', 'value' => 'info@edusmartpro.sch.id', 'group' => 'school', 'type' => 'string'],
            ['key' => 'school_principal', 'value' => 'Dr. H. Ahmad Fauzi, M.Pd', 'group' => 'school', 'type' => 'string'],
            
            // Academic
            ['key' => 'academic_year', 'value' => '2023/2024', 'group' => 'academic', 'type' => 'string'],
            ['key' => 'semester', 'value' => 'Ganjil', 'group' => 'academic', 'type' => 'string'],
            
            // App
            ['key' => 'items_per_page', 'value' => '10', 'group' => 'app', 'type' => 'number'],
            ['key' => 'enable_registration', 'value' => 'true', 'group' => 'app', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::create($setting);
        }
    }
}
