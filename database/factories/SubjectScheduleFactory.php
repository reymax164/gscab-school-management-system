<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<SubjectSchedule>
 */
class SubjectScheduleFactory extends Factory
{
    protected $model = SubjectSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->randomElement(['07:00', '08:00', '09:00', '10:00', '13:00', '14:00']);

        return [
            'class_schedule_id' => ClassSchedule::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => Teacher::factory(),
            'classroom_id' => Classroom::factory(),
            'days' => fake()->randomElement(['Mon,Wed,Fri', 'Tue,Thu', 'Mon,Tue,Wed,Thu,Fri']),
            'start_time' => $startTime,
            'end_time' => Carbon::parse($startTime)->addHour()->format('H:i'),
        ];
    }
}
