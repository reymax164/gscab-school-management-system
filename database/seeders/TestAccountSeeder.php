<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            'admin' => [
                'first_name' => 'System',
                'last_name' => 'Admin',
            ],
            'registrar' => [
                'first_name' => 'System',
                'last_name' => 'Registrar',
            ],
            'cashier' => [
                'first_name' => 'System',
                'last_name' => 'Cashier',
            ],
            'teacher' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            'student' => [
                'first_name' => 'Junior',
                'last_name' => 'Doe',
                'lrn' => '2026403159',
            ],
        ];

        foreach ($accounts as $role => $attributes) {
            $user = User::updateOrCreate(
                ['email' => "{$role}@gscab.edu"],
                [
                    ...$attributes,
                    'password' => Hash::make("{$role}.403159"),
                    'role' => $role,
                ],
            );

            if ($role === 'teacher') {
                $user->teacher()->updateOrCreate([], [
                    'employee_id' => 'EMP-2026-01',
                    'department_id' => 'Mathematics',
                    'hire_date' => now(),
                ]);
            }

            if ($role === 'student') {
                $user->student()->updateOrCreate([], [
                    'grade_level' => '1',
                    'enrollment_status' => 'enrolled',
                ]);
            }
        }
    }
}
