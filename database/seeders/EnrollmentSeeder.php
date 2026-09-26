<?php

namespace Database\Seeders;

use App\Models\Enrollments\Enrollment;
use App\Models\Student;
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
        for ($i = 0; $i < 20; $i++) {
            $status = match (true) {
                $i < 6 => 'submitted',
                $i < 12 => 'registrar_approved',
                $i < 18 => 'enrolled',
                default => 'rejected',
            };
            $lrn = fake()->unique()->numerify('2026######');

            $applicantUser = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'lrn' => $lrn,
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
                'status' => $status,
                'online_access' => 'wifi',
                'gadgets' => ['Smartphone', 'Laptop'],
            ]);

            // create the Student Profile Spoke
            $enrollment->studentProfile()->create([
                'lrn' => $lrn,
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

            // create the Payment Spoke, keeping payment_status aligned with the enrollment status
            // dummy
            $enrollment->payment()->create([
                'payment_scheme' => 'full',
                'tuition_fee' => 15000.00,
                'misc_fee' => 6000.00,
                'discount_amount' => 1500.00,
                'total_amount' => 19500.00,
                'payment_status' => $status === 'enrolled' ? 'paid' : 'pending',
            ]);

            // only fully enrolled applicants get a Student record so they can be attached to a section
            if ($status === 'enrolled') {
                Student::create([
                    'user_id' => $applicantUser->id,
                    'grade_level' => $enrollment->grade_level,
                    'enrollment_status' => 'enrolled',
                ]);
            }
        }
    }
}
