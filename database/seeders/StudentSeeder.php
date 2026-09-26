<?php

namespace Database\Seeders;

use App\Models\Enrollments\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $createEnrollmentProfile = function (User $user, string $gradeLevel, string $lrn): void {
            $enrollment = Enrollment::create([
                'reference_code' => 'APP-'.date('Y').'-'.strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'school_year' => '2026-2027',
                'grade_level' => $gradeLevel,
                'student_status' => 'new',
                'status' => 'enrolled',
                'online_access' => 'wifi',
                'gadgets' => ['Smartphone', 'Laptop'],
            ]);

            $enrollment->studentProfile()->create([
                'lrn' => $lrn,
                'email' => $user->email,
                'religion' => 'Catholic',
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'gender' => fake()->randomElement(['male', 'female']),
                'birthdate' => fake()->dateTimeBetween('-16 years', '-6 years')->format('Y-m-d'),
                'age' => fake()->numberBetween(6, 16),
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
        };

        // test student account
        $student = User::create([
            'first_name' => 'Junior',
            'last_name' => 'Doe',
            'email' => 'student@gscab.edu',
            'lrn' => '2026000001',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student->student()->create([
            'grade_level' => '1',
            'enrollment_status' => 'enrolled',
        ]);
        $createEnrollmentProfile($student, '1', $student->lrn);

        // dummy students
        User::factory()->count(40)->create([
            'role' => 'student',
            // generate LRN on the User model
        ])->each(function (User $user) use ($createEnrollmentProfile) {
            $user->update(['lrn' => fake()->unique()->numerify('2026######')]);

            $gradeLevel = fake()->randomElement(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);

            $user->student()->create([
                'grade_level' => $gradeLevel,
                'enrollment_status' => 'enrolled',
            ]);

            $createEnrollmentProfile($user, $gradeLevel, $user->lrn);
        });
    }
}
