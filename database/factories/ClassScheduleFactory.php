<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassSchedule>
 */
class ClassScheduleFactory extends Factory
{
    protected $model = ClassSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grade_level' => fake()->randomElement(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
            'classroom_id' => Classroom::factory(),
            'adviser_id' => Teacher::factory(),
            'school_year' => fake()->randomElement(['2025-2026', '2026-2027']),
        ];
    }
}
