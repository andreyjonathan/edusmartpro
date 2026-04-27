<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'short_name' => 'Math', 'code' => 'MATH101', 'group' => 'Core'],
            ['name' => 'Physics', 'short_name' => 'Physics', 'code' => 'PHY101', 'group' => 'Core'],
            ['name' => 'Chemistry', 'short_name' => 'Chem', 'code' => 'CHEM101', 'group' => 'Core'],
            ['name' => 'Biology', 'short_name' => 'Bio', 'code' => 'BIO101', 'group' => 'Core'],
            ['name' => 'English', 'short_name' => 'English', 'code' => 'ENG101', 'group' => 'Core'],
            ['name' => 'Indonesian', 'short_name' => 'Indo', 'code' => 'IND101', 'group' => 'Core'],
            ['name' => 'History', 'short_name' => 'History', 'code' => 'HIST101', 'group' => 'Core'],
            ['name' => 'Computer Science', 'short_name' => 'CS', 'code' => 'CS101', 'group' => 'Elective'],
            ['name' => 'Art', 'short_name' => 'Art', 'code' => 'ART101', 'group' => 'Elective'],
            ['name' => 'Physical Education', 'short_name' => 'PE', 'code' => 'PE101', 'group' => 'Elective'],
        ];

        Subject::insert($subjects);
    }
}
