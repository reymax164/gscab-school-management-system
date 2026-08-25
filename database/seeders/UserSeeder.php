<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TEST ACCOUNTS
        User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);

        User::create([
            'first_name' => 'Kimberly Joy',
            'last_name' => 'Jaway',
            'email' => 'registrar@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'registrar',
        ]);

        User::create([
            'first_name' => 'Kimberly Joy',
            'last_name' => 'Jaway',
            'email' => 'cashier@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'cashier',
        ]);

        // Test Teacher
        $teacher = User::create([
            'first_name' => 'Nashley Cedrick',
            'middle_name' => 'Austria',
            'last_name' => 'Almazan',
            'email' => 'teacher@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'teacher',
        ]);

        if (method_exists($teacher, 'teacherProfile')) {
            $teacher->teacherProfile()->create([
                'employee_id' => 'EMP-2026-01',
                'department_id' => 'Mathematics',
                'hire_date' => now(),
            ]);
        }

        // Test Student
        $student = User::create([
            'first_name' => 'Nashley Cedrick',
            'middle_name' => 'Austria',
            'last_name' => 'Almazan',
            'email' => 'student@school.edu',
            'password' => Hash::make('password'),
            'user_type' => 'student',
        ]);

        $student->student()->create([
            'lrn' => '2026-0001',
            'grade_level' => '1',
            'enrollment_status' => 'enrolled',
        ]);

        // DUMMY DATA GENERATION
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'registrar',
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'cashier',
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            $fakeTeacher = User::create([
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'teacher',
            ]);

            if (method_exists($fakeTeacher, 'teacherProfile')) {
                $fakeTeacher->teacherProfile()->create([
                    'employee_id' => 'EMP-2026-' . fake()->unique()->numerify('##'),
                    'department_id' => fake()->randomElement(['Mathematics', 'Science', 'English', 'History', 'Mapeh']),
                    'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
                ]);
            }
        }

        // Generate 10 Students + Master Records
        for ($i = 0; $i < 10; $i++) {
            $fakeLrn = fake()->unique()->numerify('2026-####');
            
            $fakeStudent = User::create([
                'first_name' => fake()->firstName(),
                'middle_name' => fake()->lastName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'student',
            ]);

            $fakeStudent->student()->create([
                'lrn' => $fakeLrn,
                'grade_level' => fake()->randomElement(['1', '2', '3', '4', '5', '6']),
                'enrollment_status' => fake()->randomElement(['enrolled', 'pending']),
            ]);
        }

        
        // DUMMY PENDING ENROLLMENT APPLICATIONS
        for ($i = 0; $i < 5; $i++) {
            $applicantUser = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'user_type' => 'student', // They start as a student user
            ]);

            // create the central Enrollment Hub
            $enrollment = \App\Models\Enrollments\Enrollment::create([
                'reference_code' => 'APP-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'user_id'        => $applicantUser->id,
                'school_year'    => '2026-2027',
                'grade_level'    => fake()->randomElement(['7', '8', '9', '10']),
                'student_status' => 'new',
                'status'         => 'submitted', // 'submitted' goes to Registrar
                'online_access'  => 'wifi',
                'gadgets'        => ['Smartphone', 'Laptop'],
            ]);

            // create the Student Profile Spoke
            $enrollment->studentProfile()->create([
                'lrn'               => fake()->unique()->numerify('10########'),
                'email'             => $applicantUser->email,
                'religion'          => 'Catholic',
                'last_name'         => $applicantUser->last_name,
                'first_name'        => $applicantUser->first_name,
                'middle_name'       => fake()->lastName(),
                'gender'            => fake()->randomElement(['male', 'female']),
                'birthdate'         => fake()->dateTimeBetween('-16 years', '-12 years')->format('Y-m-d'),
                'age'               => 14,
                'birthplace'        => 'Batangas City',
                'birth_order'       => '1st',
                'nationality'       => 'Filipino',
                'house_no'          => fake()->buildingNumber(),
                'sitio_subdivision' => 'Kumintang',
                'barangay'          => 'Kumintang Ibaba',
                'zip'               => '4200',
                
                // pack the flat form data into JSON arrays exactly like the controller
                'father_details'    => [
                    'deceased'   => 'no',
                    'last_name'  => fake()->lastName(),
                    'first_name' => fake()->firstName('male'),
                    'middle_name'=> 'M.',
                    'age'        => 45,
                    'address'    => 'Batangas City',
                    'number'     => fake()->numerify('09#########'),
                    'occupation' => 'Engineer',
                ],
                'mother_details'    => [
                    'deceased'      => 'no',
                    'maiden_last'   => fake()->lastName(),
                    'first_name'    => fake()->firstName('female'),
                    'maiden_middle' => 'A.',
                    'age'           => 43,
                    'address'       => 'Batangas City',
                    'number'        => fake()->numerify('09#########'),
                    'occupation'    => 'Teacher',
                ],
                'guardian_details'  => [
                    'name'       => null,
                    'relation'   => null,
                    'address'    => null,
                    'number'     => null,
                    'occupation' => null,
                ],
                'contact_person'    => [
                    'name'     => fake()->name(),
                    'relation' => 'Parent',
                    'number'   => fake()->numerify('09#########'),
                    'address'  => 'Batangas City',
                ],
            ]);

            // create the Educational Background Spoke
            $enrollment->educationalBackground()->create([
                'last_school'    => 'Batangas National High School',
                'school_address' => 'Batangas City',
                'school_year'    => '2025-2026',
                'school_type'    => 'public',
                'gen_ave'        => null,
                'talent_skills'  => 'Singing',
            ]);

            // create the Payment Spoke
            $enrollment->payment()->create([
                'payment_scheme' => 'full',
                'payment_status' => 'pending',
            ]);
        }
    }
}