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
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $positions = [
            'Staf Administrasi', 'Pustakawan', 'Keamanan', 'Petugas Kebersihan', 
            'Dukungan TI', 'Akuntan', 'Spesialis SDM', 'Resepsionis', 'Laboran'
        ];

        return [
            'nik' => $this->faker->unique()->numerify('################'), // 16 digits
            'name' => $this->faker->name($gender === 'Male' ? 'male' : 'female'),
            'position' => $this->faker->randomElement($positions),
            'gender' => $gender,
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
