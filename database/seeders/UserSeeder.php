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
        // test admin account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);

        // test registrar account
        User::create([
            'name' => 'Jane Registrar',
            'email' => 'registrar@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'registrar',
        ]);

        // test cashier account
        User::create([
            'name' => 'John Cashier',
            'email' => 'cashier@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'cashier',
        ]);

        // test teacher account
        $teacher = User::create([
            'name' => 'Prof. Alan Turing',
            'email' => 'teacher@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'teacher',
        ]);

        $teacher->teacherProfile()->create([
            'employee_id' => 'EMP-2026-01',
            'department_id' => 'Mathematics',
            'hire_date' => now(),
        ]);

        // student account
        $student = User::create([
            'name' => 'Nashley Cedrick',
            'student_number' => '2026-0001',
            'password' => Hash::make('password'),
            'user_type' => 'student',
        ]);

        $student->studentProfile()->create([
            'student_number' => '2026-0001',
            'grade_level' => '10',
            'enrollment_status' => 'enrolled',
        ]);
    }
}