<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjectTitles = ['Mathematics', 'Science', 'English', 'Filipino', 'Araling Panlipunan', 'MAPEH', 'ESP', 'Computer Studies'];

        // one subject per title, avoiding the duplicate labels random factory picks would create
        $subjects = collect($subjectTitles)->map(fn (string $title, int $i) => Subject::factory()->create([
            'code' => 'SUB-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
            'title' => $title,
        ]));

        $classrooms = Classroom::factory()->count(6)->create();
        $teachers = Teacher::factory()->count(10)->create();

        foreach (['2025-2026', '2026-2027'] as $schoolYear) {
            for ($i = 0; $i < 5; $i++) {
                $section = ClassSchedule::factory()->create([
                    'classroom_id' => $classrooms->random()->id,
                    'adviser_id' => $teachers->random()->id,
                    'school_year' => $schoolYear,
                ]);

                for ($j = 0; $j < 3; $j++) {
                    SubjectSchedule::factory()->create([
                        'class_schedule_id' => $section->id,
                        'subject_id' => $subjects->random()->id,
                        'teacher_id' => $teachers->random()->id,
                        'classroom_id' => $j === 0 ? $section->classroom_id : $classrooms->random()->id,
                    ]);
                }
            }
        }
    }
}
