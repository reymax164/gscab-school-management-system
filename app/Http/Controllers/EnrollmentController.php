<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        // DB Transaction ensures all related data is saved safely
        DB::transaction(function () use ($request) {
            $enrollment = Enrollment::create([
                'user_id' => Auth::id(),
                'school_year',
                'grade_level',
                'student_status', // 'new', 'existing', 'transferee'
                'status',         // 'submitted', 'registrar_approved', 'cashier_cleared', 'enrolled', 'rejected'
                'online_access',
                'gadgets',        
            ]);

            $enrollment->studentProfile()->create($request->only([
                'last_name', 'first_name', 'middle_name', 'gender', 
                'birthdate', 'birthplace', 'birth_order', 'age', 'nationality',
                'region', 'province', 'zip', 'barangay', 'sitio_subdivision', 'house_no',
                'father_details', 'mother_details', 'guardian_details', 'contact_person', 'landline',
            ]));

            $enrollment->educationalBackground()->create($request->only([
                'last_school', 'school_address', 'school_year',
                'school_type', 'honors_awards', 'gen_ave', 'talent_skills',
            ]));

            $enrollment->payment()->create([
                'payment_scheme' => $request->payment_scheme,
                'payment_status' => 'pending',
            ]);
        });

        return redirect()->route('home')->with('success', 'Application submitted!');
    }
}
