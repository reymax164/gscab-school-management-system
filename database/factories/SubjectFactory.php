<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SUB-###')),
            'title' => fake()->randomElement([
                'Mathematics', 'Science', 'English', 'Filipino',
                'Araling Panlipunan', 'MAPEH', 'ESP', 'Computer Studies',
            ]),
            'units' => fake()->numberBetween(1, 3),
        ];
    }
}
