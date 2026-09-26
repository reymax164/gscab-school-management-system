<?php

use App\Models\Section;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teacher schedules index only shows their own sections', function () {
    $adviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $unrelatedTeacher = Teacher::factory()->create();

    $advisedSection = Section::factory()->create(['adviser_id' => $adviser->id]);
    $subjectSection = Section::factory()->create();
    SubjectSchedule::factory()->create(['section_id' => $subjectSection->id, 'teacher_id' => $subjectTeacher->id]);

    Section::factory()->create(); // unrelated section

    $response = $this->actingAs($adviser->user)
        ->get(route('teacher.schedules.index'))
        ->assertOk();

    $response->assertViewHas('sections', function ($sections) use ($advisedSection) {
        return $sections->contains('id', $advisedSection->id) && $sections->count() === 1;
    });

    $this->actingAs($subjectTeacher->user)
        ->get(route('teacher.schedules.index'))
        ->assertOk()
        ->assertViewHas('sections', function ($sections) use ($subjectSection) {
            return $sections->contains('id', $subjectSection->id) && $sections->count() === 1;
        });

    $this->actingAs($unrelatedTeacher->user)
        ->get(route('teacher.schedules.index'))
        ->assertOk()
        ->assertViewHas('sections', fn ($sections) => $sections->isEmpty());
});

test('adviser sees the full subject list when viewing a section schedule', function () {
    $adviser = Teacher::factory()->create();
    $section = Section::factory()->create(['adviser_id' => $adviser->id]);

    SubjectSchedule::factory()->count(3)->create(['section_id' => $section->id]);

    $this->actingAs($adviser->user)
        ->get(route('teacher.schedules.show', $section))
        ->assertOk()
        ->assertViewHas('isAdviser', true)
        ->assertViewHas('subjectSchedules', fn ($schedules) => $schedules->count() === 3);
});

test('subject teacher only sees their own rows when viewing a section schedule', function () {
    $adviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $otherTeacher = Teacher::factory()->create();
    $section = Section::factory()->create(['adviser_id' => $adviser->id]);

    SubjectSchedule::factory()->create(['section_id' => $section->id, 'teacher_id' => $subjectTeacher->id]);
    SubjectSchedule::factory()->create(['section_id' => $section->id, 'teacher_id' => $otherTeacher->id]);

    $this->actingAs($subjectTeacher->user)
        ->get(route('teacher.schedules.show', $section))
        ->assertOk()
        ->assertViewHas('isAdviser', false)
        ->assertViewHas('subjectSchedules', function ($schedules) use ($subjectTeacher) {
            return $schedules->count() === 1 && $schedules->first()->teacher_id === $subjectTeacher->id;
        });
});

test('teacher not part of a section is forbidden from viewing its schedule', function () {
    $section = Section::factory()->create();
    $outsider = Teacher::factory()->create();

    $this->actingAs($outsider->user)
        ->get(route('teacher.schedules.show', $section))
        ->assertForbidden();
});
