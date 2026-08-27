<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Room '.fake()->unique()->numberBetween(100, 300),
            'capacity' => fake()->numberBetween(20, 30),
            'building_name' => fake()->randomElement(['Main Building', 'Building 101', 'Building 102']),
        ];
    }
}
