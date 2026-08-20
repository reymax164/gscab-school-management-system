<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    /**
     * Display the enrollment form pre-filled with the user's data.
     */
    public function create()
    {
        // Change auth()->user() to Auth::user()
        $user = Auth::user(); 
        
        return view('auth.enroll', compact('user'));
    }

    /**
     * Store a newly created enrollment application in storage.
     */
    public function store(Request $request)
    {
        // DB Transaction ensures all related data is saved safely
        DB::transaction(function () use ($request) {
            // create central hub record with explicit key-value mapping
            $enrollment = Enrollment::create([
                'user_id'        => Auth::id(),
                'school_year'    => $request->school_year,
                'grade_level'    => $request->grade_level,
                'student_status' => $request->student_status, 
                'status'         => 'submitted', // Set initial default application status
                'online_access'  => $request->online_access,
                'gadgets'        => $request->gadgets,        
            ]);

            // create student profile spoke
            $enrollment->studentProfile()->create($request->only([
                'last_name', 'first_name', 'middle_name', 'gender', 
                'birthdate', 'birthplace', 'birth_order', 'age', 'nationality',
                'region', 'province', 'zip', 'barangay', 'sitio_subdivision', 'house_no',
                'father_details', 'mother_details', 'guardian_details', 'contact_person', 'landline',
            ]));

            // create educational background spoke
            $enrollment->educationalBackground()->create($request->only([
                'last_school', 'school_address', 'school_year',
                'school_type', 'honors_awards', 'gen_ave', 'talent_skills',
            ]));

            // create payment spoke
            $enrollment->payment()->create([
                'payment_scheme' => $request->payment_scheme,
                'payment_status' => 'pending',
            ]);
        });

        return redirect()->route('home')->with('success', 'Application submitted!');
    }
}