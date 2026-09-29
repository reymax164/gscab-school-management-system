<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use App\Models\SubjectSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_schedule_id' => SubjectSchedule::factory(),
            'quarter' => fake()->randomElement(['Q1', 'Q2', 'Q3', 'Q4']),
            'grade' => fake()->randomFloat(2, 75, 100),
        ];
    }
}
