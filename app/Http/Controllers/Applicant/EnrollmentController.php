<?php

namespace App\Http\Controllers\Applicant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Enrollments\Enrollment;
use App\Http\Requests\StoreEnrollmentRequest;

class EnrollmentController extends Controller
{
    public function create()
    {
        $user = Auth::user(); 
        return view('auth.enroll', compact('user'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        DB::transaction(function () use ($request) {
            // automatically determine the current school year based on the current date
            $currentYear = date('Y');
            $schoolYear = $currentYear . '-' . ($currentYear + 1);

            // create central hub record
            $enrollment = Enrollment::create([
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

        return redirect()->route('home')->with('success', 'Application submitted!');
    }
}