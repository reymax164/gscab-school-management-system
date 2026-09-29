<?php

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teacher grades index only shows their own subject schedules', function () {
    $subjectTeacher = Teacher::factory()->create();
    $otherTeacher = Teacher::factory()->create();

    $ownSchedule = SubjectSchedule::factory()->create(['teacher_id' => $subjectTeacher->id]);
    SubjectSchedule::factory()->create(['teacher_id' => $otherTeacher->id]);

    $this->actingAs($subjectTeacher->user)
        ->get(route('teacher.grades.index'))
        ->assertOk()
        ->assertViewHas('subjectSchedules', function ($subjectSchedules) use ($ownSchedule) {
            return $subjectSchedules->count() === 1 && $subjectSchedules->first()->id === $ownSchedule->id;
        });
});

test('teacher not assigned to the subject schedule is forbidden from viewing its grades', function () {
    $subjectSchedule = SubjectSchedule::factory()->create();
    $outsider = Teacher::factory()->create();

    $this->actingAs($outsider->user)
        ->get(route('teacher.grades.show', $subjectSchedule))
        ->assertForbidden();

    $this->actingAs($outsider->user)
        ->patch(route('teacher.grades.update', $subjectSchedule), ['grades' => []])
        ->assertForbidden();
});

test('subject teacher can persist grades for their students', function () {
    $subjectTeacher = Teacher::factory()->create();
    $section = Section::factory()->create();
    $subjectSchedule = SubjectSchedule::factory()->create(['teacher_id' => $subjectTeacher->id, 'section_id' => $section->id]);

    $student = Student::factory()->create();
    $section->students()->attach($student->id, ['status' => 'enrolled']);

    $this->actingAs($subjectTeacher->user)
        ->patch(route('teacher.grades.update', $subjectSchedule), [
            'grades' => [
                $student->id => [
                    'Q1' => 85.5,
                    'Q2' => 90,
                ],
            ],
        ])
        ->assertRedirect(route('teacher.grades.show', $subjectSchedule));

    $this->assertDatabaseHas('grades', [
        'student_id' => $student->id,
        'subject_schedule_id' => $subjectSchedule->id,
        'quarter' => 'Q1',
        'grade' => 85.5,
    ]);

    $this->assertDatabaseHas('grades', [
        'student_id' => $student->id,
        'subject_schedule_id' => $subjectSchedule->id,
        'quarter' => 'Q2',
        'grade' => 90,
    ]);

    // updating again should update the existing row instead of creating a duplicate
    $this->actingAs($subjectTeacher->user)
        ->patch(route('teacher.grades.update', $subjectSchedule), [
            'grades' => [
                $student->id => [
                    'Q1' => 95,
                ],
            ],
        ]);

    expect(Grade::where([
        'student_id' => $student->id,
        'subject_schedule_id' => $subjectSchedule->id,
        'quarter' => 'Q1',
    ])->count())->toBe(1);

    $this->assertDatabaseHas('grades', [
        'student_id' => $student->id,
        'subject_schedule_id' => $subjectSchedule->id,
        'quarter' => 'Q1',
        'grade' => 95,
    ]);
});

test('guests are redirected from grade routes', function () {
    $subjectSchedule = SubjectSchedule::factory()->create();

    $this->get(route('teacher.grades.index'))->assertRedirect();
    $this->get(route('teacher.grades.show', $subjectSchedule))->assertRedirect();
    $this->patch(route('teacher.grades.update', $subjectSchedule), ['grades' => []])->assertRedirect();
});
