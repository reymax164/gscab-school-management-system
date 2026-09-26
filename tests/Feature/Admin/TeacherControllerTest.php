<?php

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function validTeacherPayload(array $overrides = []): array
{
    return array_merge([
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password123',
        'employee_id' => 'EMP-'.fake()->unique()->numerify('####'),
        'department_id' => 'Science',
        'hire_date' => '2024-01-15',
        'last_name' => 'Reyes',
        'first_name' => 'Maria',
        'middle_name' => 'Santos',
    ], $overrides);
}

test('admin can view the teachers index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Teacher::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.accounts.teachers.index'))
        ->assertOk();
});

test('admin can view the create teacher form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.teachers.create'))
        ->assertOk();
});

test('admin can store a new teacher', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $payload = validTeacherPayload();

    $this->actingAs($admin)
        ->post(route('admin.accounts.teachers.store'), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'role' => 'teacher',
    ]);

    $this->assertDatabaseHas('teachers', [
        'employee_id' => $payload['employee_id'],
        'department_id' => $payload['department_id'],
    ]);

    $user = User::where('email', $payload['email'])->firstOrFail();
    expect(Hash::check($payload['password'], $user->password))->toBeTrue();
});

test('storing a teacher fails validation with missing required fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.accounts.teachers.store'), [])
        ->assertSessionHasErrors(['email', 'password', 'employee_id', 'hire_date', 'last_name', 'first_name']);
});

test('storing a teacher fails when employee_id is already taken', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $existing = Teacher::factory()->create(['employee_id' => 'EMP-0001']);

    $payload = validTeacherPayload(['employee_id' => $existing->employee_id]);

    $this->actingAs($admin)
        ->post(route('admin.accounts.teachers.store'), $payload)
        ->assertSessionHasErrors('employee_id');
});

test('admin can view a teacher', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $teacher = Teacher::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.accounts.teachers.show', $teacher->id))
        ->assertOk()
        ->assertSee($teacher->employee_id);
});

test('admin can view the edit teacher form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $teacher = Teacher::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.accounts.teachers.edit', $teacher->id))
        ->assertOk();
});

test('admin can update a teacher', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $teacher = Teacher::factory()->create(['department_id' => 'Mathematics']);

    $payload = [
        'email' => $teacher->user->email,
        'password' => '',
        'department_id' => 'English',
        'hire_date' => '2025-06-01',
        'last_name' => 'Cruz',
        'first_name' => 'Juan',
        'middle_name' => null,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.teachers.update', $teacher->id), $payload)
        ->assertRedirect(route('admin.accounts.teachers.show', $teacher->id));

    $this->assertDatabaseHas('teachers', [
        'id' => $teacher->id,
        'department_id' => 'English',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $teacher->user_id,
        'last_name' => 'Cruz',
        'first_name' => 'Juan',
    ]);
});

test('updating a teacher does not change the employee_id', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $teacher = Teacher::factory()->create(['employee_id' => 'EMP-9999']);

    $payload = [
        'email' => $teacher->user->email,
        'employee_id' => 'EMP-0000', // attempting to tamper with an immutable field
        'department_id' => $teacher->department_id,
        'hire_date' => '2025-06-01',
        'last_name' => $teacher->user->last_name,
        'first_name' => $teacher->user->first_name,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.teachers.update', $teacher->id), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('teachers', [
        'id' => $teacher->id,
        'employee_id' => 'EMP-9999',
    ]);
});

test('updating a teacher fails validation with an invalid email', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $teacher = Teacher::factory()->create();

    $payload = [
        'email' => 'not-an-email',
        'department_id' => $teacher->department_id,
        'hire_date' => '2025-06-01',
        'last_name' => $teacher->user->last_name,
        'first_name' => $teacher->user->first_name,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.teachers.update', $teacher->id), $payload)
        ->assertSessionHasErrors('email');
});

test('non-admin roles cannot access the teachers index', function () {
    $teacherUser = Teacher::factory()->create()->user;

    $this->actingAs($teacherUser)
        ->get(route('admin.accounts.teachers.index'))
        ->assertForbidden();
});
