<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
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
            'user_id' => User::factory()->state(['role' => 'student']),
            'grade_level' => fake()->randomElement(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
            'enrollment_status' => 'enrolled',
        ];
    }
}
