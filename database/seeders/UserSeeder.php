<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // TEST ACCOUNTS
        // test admin account
        User::create([
            'first_name' => 'John Maxine',
            'last_name' => 'Reyes',
            'email' => 'admin@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);

        // test registrar account
        User::create([
            'first_name' => 'Kimberly Joy',
            'last_name' => 'Jaway',
            'email' => 'registrar@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'registrar',
        ]);

        // test cashier account
        User::create([
            'first_name' => 'Kimberly Joy',
            'last_name' => 'Jaway',
            'email' => 'cashier@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'cashier',
        ]);

        // test teacher account
        $teacher = User::create([
            'first_name' => 'Nashley Cedrick',
            'middle_name' => 'Austria',
            'last_name' => 'Almazan',
            'email' => 'teacher@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'teacher',
        ]);

        $teacher->teacherProfile()->create([
            'employee_id' => 'EMP-2026-01',
            'department_id' => 'Mathematics',
            'hire_date' => now(),
        ]);

        // test student account
        $student = User::create([
            'first_name' => 'Nashley Cedrick',
            'middle_name' => 'Austria',
            'last_name' => 'Almazan',
            'lrn' => '2026-0001',
            'password' => Hash::make('password'),
            'user_type' => 'student',
        ]);

        $student->studentProfile()->create([
            'lrn' => '2026-0001',
            'grade_level' => '1',
            'enrollment_status' => 'enrolled',
        ]);


        // auto generate dummy data

        // admins
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]);
        }

        // registrars
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'registrar',
            ]);
        }

        // cashiers
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'cashier',
            ]);
        }

        // teachers
        for ($i = 0; $i < 10; $i++) {
            $fakeTeacher = User::create([
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(), // often used as a stand-in for middle name
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'teacher',
            ]);

            $fakeTeacher->teacherProfile()->create([
                'employee_id' => 'EMP-2026-' . fake()->unique()->numerify('##'),
                'department_id' => fake()->randomElement(['Mathematics', 'Science', 'English', 'History', 'Mapeh']),
                'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            ]);
        }

        // Generate 10 Students + Profiles
        for ($i = 0; $i < 10; $i++) {
            $fakeLrn = fake()->unique()->numerify('2026-####');
            
            $fakeStudent = User::create([
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(),
                'last_name' => fake()->lastName(),
                'lrn' => $fakeLrn,
                'password' => Hash::make('password'),
                'user_type' => 'student',
            ]);

            $fakeStudent->studentProfile()->create([
                'lrn' => $fakeLrn,
                'grade_level' => fake()->randomElement(['1', '2', '3', '4', '5', '6']),
                'enrollment_status' => fake()->randomElement(['enrolled', 'pending']),
            ]);
        }
    }
}