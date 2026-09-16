<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // test teacher account
        $teacher = User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'teacher@gscab.edu',
            'password'   => Hash::make('password'),
            'role'       => 'teacher',
        ]);

        $teacher->teacher()->create([
            'employee_id'   => 'EMP-2026-01',
            'department_id' => 'Mathematics',
            'hire_date'     => now(),
        ]);

        // dummy teachers
        User::factory()->count(5)->create(['role' => 'teacher'])->each(function (User $teacher) {
            $teacher->teacher()->create([
                'employee_id'   => 'EMP-2026-'.fake()->unique()->numerify('####'),
                'department_id' => fake()->randomElement(['Mathematics', 'Science', 'English', 'History', 'Mapeh']),
                'hire_date'     => fake()->dateTimeBetween('-5 years', 'now'),
            ]);
        });
    }
}