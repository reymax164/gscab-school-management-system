<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function validCashierPayload(array $overrides = []): array
{
    return array_merge([
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password123',
        'last_name' => 'Bautista',
        'first_name' => 'Liza',
        'middle_name' => 'Garcia',
        'role' => 'cashier', // hidden field client attempts to send; must never be trusted
    ], $overrides);
}

test('admin can view the cashier index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(3)->create(['role' => 'cashier']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.cashier.index'))
        ->assertOk();
});

test('admin can view the create cashier form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.cashier.create'))
        ->assertOk();
});

test('admin can store a new cashier', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $payload = validCashierPayload();

    $this->actingAs($admin)
        ->post(route('admin.accounts.cashier.store'), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'role' => 'cashier',
    ]);

    $user = User::where('email', $payload['email'])->firstOrFail();
    expect(Hash::check($payload['password'], $user->password))->toBeTrue();
});

test('storing a cashier ignores a spoofed role and forces role to cashier', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $payload = validCashierPayload(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.accounts.cashier.store'), $payload)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'role' => 'cashier',
    ]);
});

test('storing a cashier fails validation with missing required fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.accounts.cashier.store'), [])
        ->assertSessionHasErrors(['email', 'password', 'last_name', 'first_name']);
});

test('admin can view a cashier', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.cashier.show', $cashier->id))
        ->assertOk()
        ->assertSee($cashier->email);
});

test('admin can view the edit cashier form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.cashier.edit', $cashier->id))
        ->assertOk();
});

test('admin can update a cashier', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    $payload = [
        'email' => $cashier->email,
        'password' => '',
        'last_name' => 'Ramos',
        'first_name' => 'Carlo',
        'middle_name' => null,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.cashier.update', $cashier->id), $payload)
        ->assertRedirect(route('admin.accounts.cashier.show', $cashier->id));

    $this->assertDatabaseHas('users', [
        'id' => $cashier->id,
        'last_name' => 'Ramos',
        'first_name' => 'Carlo',
        'role' => 'cashier',
    ]);
});

test('updating a cashier fails validation with a duplicate email', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $cashier = User::factory()->create(['role' => 'cashier']);
    $otherCashier = User::factory()->create(['role' => 'cashier']);

    $payload = [
        'email' => $otherCashier->email,
        'last_name' => $cashier->last_name,
        'first_name' => $cashier->first_name,
    ];

    $this->actingAs($admin)
        ->patch(route('admin.accounts.cashier.update', $cashier->id), $payload)
        ->assertSessionHasErrors('email');
});

test('visiting a registrar via the cashier show route 404s', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registrar = User::factory()->create(['role' => 'registrar']);

    $this->actingAs($admin)
        ->get(route('admin.accounts.cashier.show', $registrar->id))
        ->assertNotFound();
});

test('non-admin roles cannot access the cashier index', function () {
    $cashierUser = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($cashierUser)
        ->get(route('admin.accounts.cashier.index'))
        ->assertForbidden();
});
