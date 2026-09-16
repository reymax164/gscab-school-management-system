<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // test student account
        $student = User::create([
            'first_name' => 'Junior',
            'last_name'  => 'Doe',
            'email'      => 'student@gscab.edu',
            'lrn'        => '20260001',
            'password'   => Hash::make('password'),
            'role'       => 'student',
        ]);

        $student->student()->create([
            'grade_level'       => '1',
            'enrollment_status' => 'enrolled',
        ]);

        // dummy students
        User::factory()->count(40)->create([
            'role' => 'student',
            // generate LRN on the User model
        ])->each(function (User $user) {
            $user->update(['lrn' => fake()->unique()->numerify('2026-####')]);
            
            $user->student()->create([
                'grade_level'       => fake()->randomElement(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
                'enrollment_status' => 'enrolled',
            ]);
        });
    }
}