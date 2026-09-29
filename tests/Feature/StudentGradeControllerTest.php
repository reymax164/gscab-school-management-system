<?php

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\SubjectSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student only sees their own grades for the selected school year', function () {
    $student = Student::factory()->create();
    $otherStudent = Student::factory()->create();

    $section = Section::factory()->create(['school_year' => '2025-2026']);
    $student->sections()->attach($section->id, ['status' => 'enrolled']);
    $otherStudent->sections()->attach($section->id, ['status' => 'enrolled']);

    $subjectSchedule = SubjectSchedule::factory()->create(['section_id' => $section->id]);

    Grade::factory()->create(['student_id' => $student->id, 'subject_schedule_id' => $subjectSchedule->id, 'quarter' => 'Q1', 'grade' => 90]);
    Grade::factory()->create(['student_id' => $student->id, 'subject_schedule_id' => $subjectSchedule->id, 'quarter' => 'Q2', 'grade' => 80]);
    Grade::factory()->create(['student_id' => $otherStudent->id, 'subject_schedule_id' => $subjectSchedule->id, 'quarter' => 'Q1', 'grade' => 60]);

    $this->actingAs($student->user)
        ->get(route('student.grades', ['sy' => '2025-2026']))
        ->assertOk()
        ->assertViewHas('grades', function ($grades) {
            return $grades->count() === 1
                && (float) $grades->first()->q1 === 90.0
                && (float) $grades->first()->q2 === 80.0
                && $grades->first()->q3 === null
                && $grades->first()->q4 === null
                && (float) $grades->first()->final === 85.0;
        });
});

test('student with no section for the selected school year gets an empty state', function () {
    $student = Student::factory()->create();

    $this->actingAs($student->user)
        ->get(route('student.grades', ['sy' => '2099-2100']))
        ->assertOk()
        ->assertViewHas('grades', fn ($grades) => $grades->isEmpty());
});

test('guests are redirected from the grades route', function () {
    $this->get(route('student.grades'))->assertRedirect();
});
