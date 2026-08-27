<?php

namespace Database\Seeders;

use App\Models\Enrollments\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createTestAccounts();
        $this->createStaff();
        $this->createTeachers();
        $this->createStudents();
        $this->createEnrollmentApplications();
    }

    /**
     * Named accounts used for manual login/testing (password: "password").
     */
    private function createTestAccounts(): void
    {
        User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@gscab.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'registrar@gscab.edu',
            'password' => Hash::make('password'),
            'role' => 'registrar',
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'cashier@gscab.edu',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        $teacher = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'teacher@gscab.edu',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        $teacher->teacherProfile()->create([
            'employee_id' => 'EMP-2026-01',
            'department_id' => 'Mathematics',
            'hire_date' => now(),
        ]);

        $student = User::create([
            'first_name' => 'Junior',
            'last_name' => 'Doe',
            'email' => 'student@gscab.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student->student()->create([
            'lrn' => '20260001',
            'grade_level' => '1',
            'enrollment_status' => 'enrolled',
        ]);
    }

    /**
     * Dummy admin/registrar/cashier staff, 5 accounts each.
     */
    private function createStaff(): void
    {
        foreach (['admin', 'registrar', 'cashier'] as $role) {
            User::factory()->count(5)->create(['role' => $role]);
        }
    }

    /**
     * Dummy teachers with linked teacher profiles.
     */
    private function createTeachers(): void
    {
        User::factory()->count(5)->create(['role' => 'teacher'])
            ->each(function (User $teacher) {
                $teacher->teacherProfile()->create([
                    'employee_id' => 'EMP-2026-'.fake()->unique()->numerify('####'),
                    'department_id' => fake()->randomElement(['Mathematics', 'Science', 'English', 'History', 'Mapeh']),
                    'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
                ]);
            });
    }

    /**
     * Dummy enrolled students with linked student records.
     */
    private function createStudents(): void
    {
        User::factory()->count(40)->create(['role' => 'student'])
            ->each(function (User $student) {
                $student->student()->create([
                    'lrn' => fake()->unique()->numerify('2026-####'),
                    'grade_level' => fake()->randomElement(['Kinder', '1', '2', '3', '4', '5', '6', "7", "8", "9", "10"]),
                    'enrollment_status' => 'enrolled',
                ]);
            });
    }

    /**
     * Dummy enrollment applications with full hub-and-spoke records.
     */
    private function createEnrollmentApplications(): void
    {
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
