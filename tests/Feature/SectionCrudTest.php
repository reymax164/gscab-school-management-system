<?php

use App\Models\Classroom;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function validSubjectSchedulePayload(Subject $subject, Teacher $teacher, Classroom $classroom): array
{
    return [
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'classroom_id' => $classroom->id,
        'days' => 'Mon,Wed,Fri',
        'start_time' => '08:00',
        'end_time' => '09:00',
    ];
}

test('admin can view the sections index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Section::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.sections.index'))
        ->assertOk();
});

test('admin can create a section with subject schedules', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $adviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $subject = Subject::factory()->create();
    $classroom = Classroom::factory()->create();

    $payload = [
        'grade_level' => '7',
        'adviser_id' => $adviser->id,
        'school_year' => '2026-2027',
        'subject_schedules' => [
            validSubjectSchedulePayload($subject, $subjectTeacher, $classroom),
        ],
    ];

    $this->actingAs($admin)
        ->post(route('admin.sections.store'), $payload)
        ->assertRedirect(route('admin.sections.index'));

    $this->assertDatabaseHas('sections', [
        'grade_level' => '7',
        'school_year' => '2026-2027',
        'adviser_id' => $adviser->id,
        'classroom_id' => $classroom->id,
    ]);

    $this->assertDatabaseHas('subject_schedules', [
        'subject_id' => $subject->id,
        'teacher_id' => $subjectTeacher->id,
    ]);
});

test('admin can view a section', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $section = Section::factory()->create();
    SubjectSchedule::factory()->create(['section_id' => $section->id]);

    $this->actingAs($admin)
        ->get(route('admin.sections.show', $section))
        ->assertOk()
        ->assertSee($section->school_year);
});

test('admin can update a section', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $section = Section::factory()->create(['grade_level' => '7']);
    SubjectSchedule::factory()->create(['section_id' => $section->id]);

    $newAdviser = Teacher::factory()->create();
    $subjectTeacher = Teacher::factory()->create();
    $subject = Subject::factory()->create();
    $classroom = Classroom::factory()->create();

    $payload = [
        'grade_level' => '8',
        'adviser_id' => $newAdviser->id,
        'school_year' => $section->school_year,
        'subject_schedules' => [
            validSubjectSchedulePayload($subject, $subjectTeacher, $classroom),
        ],
    ];

    $this->actingAs($admin)
        ->put(route('admin.sections.update', $section), $payload)
        ->assertRedirect(route('admin.sections.index'));

    $this->assertDatabaseHas('sections', [
        'id' => $section->id,
        'grade_level' => '8',
        'adviser_id' => $newAdviser->id,
    ]);
});

test('admin can delete a section', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $section = Section::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.sections.destroy', $section))
        ->assertRedirect(route('admin.sections.index'));

    $this->assertDatabaseMissing('sections', ['id' => $section->id]);
});

test('non-admin roles cannot access the sections index', function () {
    $teacherUser = Teacher::factory()->create()->user;

    $this->actingAs($teacherUser)
        ->get(route('admin.sections.index'))
        ->assertForbidden();
});
