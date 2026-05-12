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
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $subjects = [
            'Matematika', 'Fisika', 'Biologi', 'Kimia', 'Sejarah', 
            'Bahasa Inggris', 'Bahasa Indonesia', 'Geografi', 'Seni Budaya', 
            'Pendidikan Jasmani', 'Informatika', 'Ekonomi', 'Sosiologi'
        ];

        return [
            'nip' => $this->faker->unique()->numerify('################'), // 18 digits usually for NIP, but keeping 16 as per current schema if needed
            'name' => $this->faker->name($gender === 'Male' ? 'male' : 'female'),
            'subject' => $this->faker->randomElement($subjects),
            'gender' => $gender,
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
