<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numerify('################'), // 16 digits
            'name' => $this->faker->name(),
            'subject' => $this->faker->randomElement(['Mathematics', 'Physics', 'Biology', 'Chemistry', 'History', 'English', 'Geography', 'Art', 'Physical Education']),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
