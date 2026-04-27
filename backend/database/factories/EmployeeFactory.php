<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('################'), // 16 digits
            'name' => $this->faker->name(),
            'position' => $this->faker->randomElement(['Administrative Staff', 'Librarian', 'Security', 'Janitor', 'IT Support', 'Accountant', 'HR Specialist']),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
