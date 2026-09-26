<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
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

        // reuse the teacher pool seeded by TeacherSeeder (including John Doe) instead of a disconnected fresh batch
        $teachers = Teacher::all();
        $johnDoe = Teacher::whereHas('user', fn ($query) => $query->where('email', 'teacher@gscab.edu'))->first();

        // reuse the student pool seeded by StudentSeeder (including Jr. Doe) for section attachment
        $students = Student::all();
        $juniorDoe = Student::whereHas('user', fn ($query) => $query->where('email', 'student@gscab.edu'))->first();

        $isFirstSection = true;

        foreach (['2025-2026', '2026-2027'] as $schoolYear) {
            for ($i = 0; $i < 5; $i++) {
                $section = Section::factory()->create([
                    'classroom_id' => $classrooms->random()->id,
                    'adviser_id' => $isFirstSection && $johnDoe ? $johnDoe->id : $teachers->random()->id,
                    'school_year' => $schoolYear,
                ]);

                for ($j = 0; $j < 3; $j++) {
                    SubjectSchedule::factory()->create([
                        'section_id' => $section->id,
                        'subject_id' => $subjects->random()->id,
                        'teacher_id' => $teachers->random()->id,
                        'classroom_id' => $j === 0 ? $section->classroom_id : $classrooms->random()->id,
                    ]);
                }

                // attach a batch of students to this section via the section_student pivot
                $studentIds = $students->random(min(8, $students->count()))->pluck('id')->all();

                if ($isFirstSection && $juniorDoe && ! in_array($juniorDoe->id, $studentIds, true)) {
                    $studentIds[] = $juniorDoe->id;
                }

                $section->students()->attach($studentIds, ['status' => 'enrolled']);

                $isFirstSection = false;
            }
        }
    }
}
