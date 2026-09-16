<?php

namespace Database\Seeders;

use App\Models\Enrollments\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
        * Dummy enrollment applications with full hub-and-spoke records.
        */
        for ($i = 0; $i < 20; $i++) {
            $applicantUser = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            // create the central Enrollment Hub
            $enrollment = Enrollment::create([
                'reference_code' => 'APP-'.date('Y').'-'.strtoupper(Str::random(6)),
                'user_id' => $applicantUser->id,
                'school_year' => '2026-2027',
                'grade_level' => fake()->randomElement(['7', '8', '9', '10']),
                'student_status' => 'new',
                'status' => 'enrolled',
                'online_access' => 'wifi',
                'gadgets' => ['Smartphone', 'Laptop'],
            ]);

            // create the Student Profile Spoke
            $enrollment->studentProfile()->create([
                'lrn' => fake()->unique()->numerify('10########'),
                'email' => $applicantUser->email,
                'religion' => 'Catholic',
                'last_name' => $applicantUser->last_name,
                'first_name' => $applicantUser->first_name,
                'middle_name' => fake()->lastName(),
                'gender' => fake()->randomElement(['male', 'female']),
                'birthdate' => fake()->dateTimeBetween('-16 years', '-12 years')->format('Y-m-d'),
                'age' => 14,
                'birthplace' => 'Batangas City',
                'birth_order' => '1st',
                'nationality' => 'Filipino',
                'house_no' => fake()->buildingNumber(),
                'sitio_subdivision' => 'Kumintang',
                'barangay' => 'Kumintang Ibaba',
                'zip' => '4200',

                // pack the flat form data into JSON arrays exactly like the controller
                'father_details' => [
                    'deceased' => 'no',
                    'last_name' => fake()->lastName(),
                    'first_name' => fake()->firstName('male'),
                    'middle_name' => 'M.',
                    'age' => 45,
                    'address' => 'Batangas City',
                    'number' => fake()->numerify('09#########'),
                    'occupation' => 'Engineer',
                ],
                'mother_details' => [
                    'deceased' => 'no',
                    'maiden_last' => fake()->lastName(),
                    'first_name' => fake()->firstName('female'),
                    'maiden_middle' => 'A.',
                    'age' => 43,
                    'address' => 'Batangas City',
                    'number' => fake()->numerify('09#########'),
                    'occupation' => 'Teacher',
                ],
                'guardian_details' => [
                    'name' => null,
                    'relation' => null,
                    'address' => null,
                    'number' => null,
                    'occupation' => null,
                ],
                'contact_person' => [
                    'name' => fake()->name(),
                    'relation' => 'Parent',
                    'number' => fake()->numerify('09#########'),
                    'address' => 'Batangas City',
                ],
            ]);

            // create the Educational Background Spoke
            $enrollment->educationalBackground()->create([
                'last_school' => 'Batangas National High School',
                'school_address' => 'Batangas City',
                'school_year' => '2025-2026',
                'school_type' => 'public',
                'gen_ave' => null,
                'talent_skills' => 'Singing',
            ]);

            // create the Payment Spoke
            $enrollment->payment()->create([
                'payment_scheme' => 'full',
                'payment_status' => 'pending',
            ]);
        }

    }
}
