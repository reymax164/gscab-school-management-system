<?php

use App\Models\Section;
use App\Models\Student;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teacher students index only shows their own sections', function () {
    $adviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $unrelatedTeacher = Teacher::factory()->create();

    $advisedSection = Section::factory()->create(['adviser_id' => $adviser->id]);
    $subjectSection = Section::factory()->create();
    SubjectSchedule::factory()->create(['section_id' => $subjectSection->id, 'teacher_id' => $subjectTeacher->id]);

    Section::factory()->create(); // unrelated section

    $this->actingAs($adviser->user)
        ->get(route('teacher.students.index'))
        ->assertOk()
        ->assertViewHas('sections', function ($sections) use ($advisedSection) {
            return $sections->contains('id', $advisedSection->id) && $sections->count() === 1;
        });

    $this->actingAs($unrelatedTeacher->user)
        ->get(route('teacher.students.index'))
        ->assertOk()
        ->assertViewHas('sections', fn ($sections) => $sections->isEmpty());
});

test('adviser can view the student roster for their section', function () {
    $adviser = Teacher::factory()->create();
    $section = Section::factory()->create(['adviser_id' => $adviser->id]);
    $students = Student::factory()->count(2)->create();
    $section->students()->attach($students->pluck('id'), ['status' => 'enrolled']);

    $this->actingAs($adviser->user)
        ->get(route('teacher.students.show', $section))
        ->assertOk()
        ->assertViewHas('students', fn ($roster) => $roster->count() === 2);
});

test('subject teacher can view the student roster for their section', function () {
    $adviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $section = Section::factory()->create(['adviser_id' => $adviser->id]);
    SubjectSchedule::factory()->create(['section_id' => $section->id, 'teacher_id' => $subjectTeacher->id]);

    $student = Student::factory()->create();
    $section->students()->attach($student->id, ['status' => 'enrolled']);

    $this->actingAs($subjectTeacher->user)
        ->get(route('teacher.students.show', $section))
        ->assertOk()
        ->assertViewHas('students', fn ($roster) => $roster->count() === 1);
});

test('teacher not part of a section is forbidden from viewing its student roster', function () {
    $section = Section::factory()->create();
    $outsider = Teacher::factory()->create();

    $this->actingAs($outsider->user)
        ->get(route('teacher.students.show', $section))
        ->assertForbidden();
});
