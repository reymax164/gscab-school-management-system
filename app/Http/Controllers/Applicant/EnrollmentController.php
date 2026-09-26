<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollments\DocumentRequirement;
use App\Models\Enrollments\Enrollment;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EnrollmentController extends Controller
{
    public function authorize(): bool
    {
        return true;
    }

    public function create()
    {
        $user = Auth::user();

        return view('enrollment.form', compact('user'));
    }

    public function trackForm()
    {
        return view('guest.track-status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['reference_code' => 'required|string']);

        $enrollment = Enrollment::where('reference_code', $request->reference_code)->first();

        if (! $enrollment) {
            return back()->with('error', 'Invalid Reference Code.');
        }

        return back()->with('status_result', $enrollment->status);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $currentYear = date('Y');
        $schoolYear = $currentYear.'-'.($currentYear + 1);
        $referenceCode = 'APP-'.$currentYear.'-'.strtoupper(Str::random(6));

        DB::transaction(function () use ($request, $schoolYear, $referenceCode) {

            // create central hub record
            // Auth::id() safely returns null for guest applicants, matching the nullable database column
            $enrollment = Enrollment::create(array_merge([
                'reference_code' => $referenceCode,
                'user_id' => Auth::id(),
                'school_year' => $schoolYear,
                'status' => 'submitted',
            ], $request->only(['grade_level', 'student_status', 'online_access', 'gadgets'])));

            // create connected spokes using helper methods
            $enrollment->studentProfile()->create($this->mapStudentProfile($request));
            $enrollment->educationalBackground()->create($this->mapEducationalBackground($request));

            $enrollment->payment()->create(array_merge([
                'payment_scheme' => $request->payment_scheme,
                'payment_status' => 'pending',
            ], $this->calculatePaymentBreakdown($request->payment_scheme)));
        });

        return redirect()->route('enroll.success')->with('reference_code', $referenceCode);
    }

    public function success()
    {
        $requirements = DocumentRequirement::where('is_active', true)->get();

        return view('enrollment.success', compact('requirements'));
    }

    /**
     * Helper method to format the Student Profile array and JSON columns
     */
    private function mapStudentProfile(Request $request): array
    {
        return array_merge(
            $request->only([
                'lrn', 'email', 'religion', 'last_name', 'first_name', 'middle_name',
                'gender', 'birthdate', 'birthplace', 'birth_order', 'age',
                'nationality', 'house_no', 'sitio_subdivision', 'barangay', 'zip', 'landline',
            ]),
            [
                'father_details' => [
                    'deceased' => $request->father_deceased,
                    'last_name' => $request->father_last_name,
                    'first_name' => $request->father_first_name,
                    'middle_name' => $request->father_middle_name,
                    'age' => $request->father_age,
                    'address' => $request->father_address,
                    'number' => $request->father_number,
                    'occupation' => $request->father_occupation,
                ],
                'mother_details' => [
                    'deceased' => $request->mother_deceased,
                    'maiden_last' => $request->mother_maiden_last,
                    'first_name' => $request->mother_first_name,
                    'maiden_middle' => $request->mother_maiden_middle,
                    'age' => $request->mother_age,
                    'address' => $request->mother_address,
                    'number' => $request->mother_number,
                    'occupation' => $request->mother_occupation,
                ],
                'guardian_details' => [
                    'name' => $request->guardian_name,
                    'relation' => $request->guardian_relation,
                    'address' => $request->guardian_address,
                    'number' => $request->guardian_number,
                    'occupation' => $request->guardian_occupation,
                ],
                'contact_person' => [
                    'name' => $request->con_person_name,
                    'relation' => $request->con_person_relation,
                    'number' => $request->con_person_number,
                    'address' => $request->con_person_address,
                ],
            ]
        );
    }

    /**
     * Helper method to format the Educational Background array
     */
    private function mapEducationalBackground(Request $request): array
    {
        return [
            'last_school' => $request->last_school,
            'school_address' => $request->last_school_address,
            'school_year' => $request->last_school_year,
            'school_type' => $request->last_school_type,
            'gen_ave' => $request->gen_ave,
            'talent_skills' => $request->talent_skills,
        ];
    }

    /**
     * Translate the chosen payment scheme into fee amounts using the configurable SystemSetting rates
     */
    private function calculatePaymentBreakdown(string $scheme): array
    {
        $tuitionFee = (float) (SystemSetting::where('key', 'tuition_fee')->value('value') ?? 0);
        $miscFee = (float) (SystemSetting::where('key', 'misc_fee')->value('value') ?? 0);
        $booksFee = (float) (SystemSetting::where('key', 'books_fee')->value('value') ?? 0);

        return match ($scheme) {
            // tuition + misc + books, less a 10% discount on tuition
            'full' => [
                'tuition_fee' => $tuitionFee,
                'misc_fee' => $miscFee + $booksFee,
                'discount_amount' => $tuitionFee * 0.10,
                'total_amount' => $tuitionFee + $miscFee + $booksFee - ($tuitionFee * 0.10),
            ],
            // books + misc, tuition is paid separately in installments
            'option1' => [
                'tuition_fee' => 0,
                'misc_fee' => $miscFee + $booksFee,
                'discount_amount' => 0,
                'total_amount' => $miscFee + $booksFee,
            ],
            // books + 1/4 misc
            'option2' => [
                'tuition_fee' => 0,
                'misc_fee' => $booksFee + ($miscFee / 4),
                'discount_amount' => 0,
                'total_amount' => $booksFee + ($miscFee / 4),
            ],
            // special schemes require manual assessment by the cashier/admin
            default => [
                'tuition_fee' => 0,
                'misc_fee' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
            ],
        };
    }
}
