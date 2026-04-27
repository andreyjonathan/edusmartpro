<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('#####'),
            'name' => $this->faker->name(),
            'class' => $this->faker->randomElement(['10-A', '10-B', '11-A', '11-B', '12-A', '12-B']),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
