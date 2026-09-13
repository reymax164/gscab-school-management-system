<?php

namespace App\Http\Controllers\Applicant;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationSubmitted;
use Illuminate\Support\Facades\Mail;
use App\Models\Enrollments\Enrollment;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollments\DocumentRequirement;

class EnrollmentController extends Controller
{
    public function authorize(): bool
    {
        return true;
    }

    public function create()
    {
        $user = Auth::user(); 
        return view('auth.enroll', compact('user'));
    }

    public function trackForm()
    {
        return view('guest.track-status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['reference_code' => 'required|string']);

        // Find the enrollment with the matching code
        $enrollment = Enrollment::where('reference_code', $request->reference_code)->first();

        if (!$enrollment) {
            return back()->with('error', 'Invalid Reference Code.');
        }

        return back()->with('status_result', $enrollment->status); // E.g., 'registrar_approved'
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $currentYear = date('Y');
        $schoolYear = $currentYear . '-' . ($currentYear + 1);
        $referenceCode = 'APP-' . $currentYear . '-' . strtoupper(Str::random(6));

        DB::transaction(function () use ($request, $schoolYear, $referenceCode) {
        
            // create central hub record
            $enrollment = Enrollment::create([
            'reference_code' => $referenceCode,
            'user_id'        => Auth::id(),
            'school_year'    => $schoolYear,
            'grade_level'    => $request->grade_level,
            'student_status' => $request->student_status, 
            'status'         => 'submitted', 
            'online_access'  => $request->online_access,
            'gadgets'        => $request->gadgets,        
        ]);

        // create student profile spoke (Mapped to handle your new DB columns & JSON structure)
        $enrollment->studentProfile()->create([
            'lrn'               => $request->lrn,
            'email'             => $request->email,
            'religion'          => $request->religion,
            'last_name'         => $request->last_name,
            'first_name'        => $request->first_name,
            'middle_name'       => $request->middle_name,
            'gender'            => $request->gender,
            'birthdate'         => $request->birthdate,
            'birthplace'        => $request->birthplace,
            'birth_order'       => $request->birth_order,
            'age'               => $request->age,
            'nationality'       => $request->nationality,
            'house_no'          => $request->house_no,
            'sitio_subdivision' => $request->sitio_subdivision,
            'barangay'          => $request->barangay,
            'zip'               => $request->zip,
            'landline'          => $request->landline,
            
            // pack the flat form data into JSON arrays
            'father_details'    => [
                'deceased'   => $request->father_deceased,
                'last_name'  => $request->father_last_name,
                'first_name' => $request->father_first_name,
                'middle_name'=> $request->father_middle_name,
                'age'        => $request->father_age,
                'address'    => $request->father_address,
                'number'     => $request->father_number,
                'occupation' => $request->father_occupation,
            ],
            'mother_details'    => [
                'deceased'      => $request->mother_deceased,
                'maiden_last'   => $request->mother_maiden_last,
                'first_name'    => $request->mother_first_name,
                'maiden_middle' => $request->mother_maiden_middle,
                'age'           => $request->mother_age,
                'address'       => $request->mother_address,
                'number'        => $request->mother_number,
                'occupation'    => $request->mother_occupation,
            ],
            'guardian_details'  => [
                'name'       => $request->guardian_name,
                'relation'   => $request->guardian_relation,
                'address'    => $request->guardian_address,
                'number'     => $request->guardian_number,
                'occupation' => $request->guardian_occupation,
            ],
            'contact_person'    => [
                'name'     => $request->con_person_name,
                'relation' => $request->con_person_relation,
                'number'   => $request->con_person_number,
                'address'  => $request->con_person_address,
            ],
        ]);

        // create educational background spoke (Fixed key mismatches)
        $enrollment->educationalBackground()->create([
            'last_school'    => $request->last_school,
            'school_address' => $request->last_school_address,
            'school_year'    => $request->last_school_year,
            'school_type'    => $request->last_school_type,
            'gen_ave'        => $request->gen_ave,
            'talent_skills'  => $request->talent_skills,
        ]);

        // create payment spoke
        $enrollment->payment()->create([
            'payment_scheme' => $request->payment_scheme,
            'payment_status' => 'pending',
        ]);
    });

        // send email
        // Mail::to($request->email)->send(new ApplicationSubmitted($referenceCode));

        // return redirect()->route('home')->with('success', 'Application submitted! Please check your email for your Reference Code.');

        // return redirect()->route('home')->with('success', 'Application submitted! Please save your Reference Code to track your status: ' . $referenceCode);

        return redirect()->route('enroll.success')->with('reference_code', $referenceCode);
    }

    public function success()
    {
        // fetch only the active documents required for submission
        $requirements = DocumentRequirement::where('is_active', true)->get();

        return view('auth.success', compact('requirements'));
    }
}