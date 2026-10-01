<?php

use App\Jobs\SendSmsNotification;
use App\Mail\ApplicationSubmitted;
use App\Models\Enrollments\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

function validEnrollmentPayload(array $overrides = []): array
{
    return array_merge([
        'grade_level' => 'Grade 7',
        'student_status' => 'existing',
        'online_access' => 'wifi',
        'gadgets' => ['laptop'],
        'email' => 'applicant@example.com',
        'lrn' => '123456789012',

        'last_name' => 'Dela Cruz',
        'first_name' => 'Juan',
        'middle_name' => 'Santos',
        'birthdate' => '2012-01-15',
        'age' => 13,
        'gender' => 'male',
        'birthplace' => 'Manila',
        'birth_order' => '1st',
        'nationality' => 'Filipino',
        'house_no' => '123',
        'sitio_subdivision' => 'Sitio Uno',
        'barangay' => 'Barangay Uno',
        'zip' => '1000',
        'religion' => 'Catholic',

        'father_deceased' => 'no',
        'father_last_name' => 'Dela Cruz',
        'father_first_name' => 'Pedro',
        'father_middle_name' => 'Santos',
        'father_age' => 40,
        'father_address' => '123 Street',
        'father_number' => '09171234567',
        'father_occupation' => 'Driver',

        'mother_deceased' => 'no',
        'mother_maiden_last' => 'Reyes',
        'mother_first_name' => 'Maria',
        'mother_maiden_middle' => 'Santos',
        'mother_age' => 38,
        'mother_address' => '123 Street',
        'mother_number' => '09171234568',
        'mother_occupation' => 'Teacher',

        'con_person_name' => 'Jose Reyes',
        'con_person_relation' => 'Uncle',
        'con_person_number' => '09179998888',
        'con_person_address' => '456 Street',

        'payment_scheme' => 'option1',
    ], $overrides);
}

test('enrollment submission queues the reference code email and sms notification', function () {
    Mail::fake();
    Queue::fake();

    $response = $this->post(route('enroll.store'), validEnrollmentPayload());

    $enrollment = Enrollment::first();
    expect($enrollment)->not->toBeNull();

    $response->assertRedirect(route('enroll.success'));

    Mail::assertQueued(ApplicationSubmitted::class, function ($mail) use ($enrollment) {
        return $mail->referenceCode === $enrollment->reference_code
            && $mail->hasTo('applicant@example.com');
    });

    Queue::assertPushed(SendSmsNotification::class, function ($job) use ($enrollment) {
        return $job->number === '09179998888'
            && str_contains($job->message, $enrollment->reference_code);
    });
});

test('enrollment submission still succeeds when the sms provider fails', function () {
    Mail::fake();
    Http::fake([
        'api.semaphore.co/*' => Http::response(['message' => 'failed'], 500),
    ]);

    $response = $this->post(route('enroll.store'), validEnrollmentPayload([
        'email' => 'resilient@example.com',
    ]));

    $response->assertRedirect(route('enroll.success'));
    expect(Enrollment::where('reference_code', '!=', null)->count())->toBe(1);

    Mail::assertQueued(ApplicationSubmitted::class);
});
