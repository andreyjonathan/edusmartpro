<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'SUBJ-' . $this->faker->unique()->numerify('###'),
            'name' => $this->faker->randomElement([
                'Mathematics', 'Physics', 'Biology', 'Chemistry', 'History', 
                'English', 'Geography', 'Art', 'Physical Education', 'Economics',
                'Computer Science', 'Sociology', 'Civics', 'Religious Education'
            ]),
            'type' => $this->faker->randomElement(['Mandatory', 'Elective', 'Vocational']),
            'description' => $this->faker->sentence(),
        ];
    }
}
