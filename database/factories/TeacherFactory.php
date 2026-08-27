<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'teacher']),
            'employee_id' => 'EMP-'.fake()->unique()->numerify('####'),
            'department_id' => fake()->randomElement(['Mathematics', 'Science', 'English', 'History', 'Mapeh']),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
