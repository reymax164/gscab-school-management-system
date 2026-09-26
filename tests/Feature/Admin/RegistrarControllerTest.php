<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function validRegistrarPayload(array $overrides = []): array
{
    return array_merge([
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password123',
        'last_name' => 'Dela Cruz',
        'first_name' => 'Ana',
        'middle_name' => 'Lopez',
        'role' => 'registrar', // hidden field client attempts to send; must never be trusted
    ], $overrides);
}

test('admin can view the registrar index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(3)->create(['role' => 'registrar']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.registrar.index'))
        ->assertOk();
});

test('admin can view the create registrar form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.registrar.create'))
        ->assertOk();
});

test('admin can store a new registrar', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $payload = validRegistrarPayload();

    $this->actingAs($admin)
        ->post(route('admin.accounts.registrar.store'), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'role' => 'registrar',
    ]);

    $user = User::where('email', $payload['email'])->firstOrFail();
    expect(Hash::check($payload['password'], $user->password))->toBeTrue();
});

test('storing a registrar ignores a spoofed role and forces role to registrar', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $payload = validRegistrarPayload(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.accounts.registrar.store'), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'role' => 'registrar',
    ]);
});

test('storing a registrar fails validation with missing required fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.accounts.registrar.store'), [])
        ->assertSessionHasErrors(['email', 'password', 'last_name', 'first_name']);
});

test('admin can view a registrar', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registrar = User::factory()->create(['role' => 'registrar']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.registrar.show', $registrar->id))
        ->assertOk()
        ->assertSee($registrar->email);
});

test('admin can view the edit registrar form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registrar = User::factory()->create(['role' => 'registrar']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.registrar.edit', $registrar->id))
        ->assertOk();
});

test('admin can update a registrar', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registrar = User::factory()->create(['role' => 'registrar']);

    $payload = [
        'email' => $registrar->email,
        'password' => '',
        'last_name' => 'Santos',
        'first_name' => 'Pedro',
        'middle_name' => null,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.registrar.update', $registrar->id), $payload)
        ->assertRedirect(route('admin.accounts.registrar.show', $registrar->id));

    $this->assertDatabaseHas('users', [
        'id' => $registrar->id,
        'last_name' => 'Santos',
        'first_name' => 'Pedro',
        'role' => 'registrar',
    ]);
});

test('updating a registrar fails validation with a duplicate email', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registrar = User::factory()->create(['role' => 'registrar']);
    $otherRegistrar = User::factory()->create(['role' => 'registrar']);

    $payload = [
        'email' => $otherRegistrar->email,
        'last_name' => $registrar->last_name,
        'first_name' => $registrar->first_name,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.registrar.update', $registrar->id), $payload)
        ->assertSessionHasErrors('email');
});

test('visiting a cashier via the registrar show route 404s', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.registrar.show', $cashier->id))
        ->assertNotFound();
});

test('non-admin roles cannot access the registrar index', function () {
    $registrarUser = User::factory()->create(['role' => 'registrar']);

    $this->actingAs($registrarUser)
        ->get(route('admin.accounts.registrar.index'))
        ->assertForbidden();
});
