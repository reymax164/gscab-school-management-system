<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollments\Enrollment;
use Illuminate\Http\Request;

class RegistrarEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        // start building the query for submitted applications
        $query = Enrollment::with('studentProfile')
            ->where('status', 'submitted')
            ->join('student_profiles', 'enrollments.id', '=', 'student_profiles.enrollment_id')
            ->select('enrollments.*'); // fetch Enrollment columns only

        // handle Sorting
        $sortBy = $request->input('sort_by', 'last_name'); 
        $sortDir = $request->input('sort_dir', 'asc'); 

        $profileFields = ['last_name', 'first_name', 'lrn'];

        if (in_array($sortBy, $profileFields)) {
            $query->orderBy('student_profiles.' . $sortBy, $sortDir);
        } else {
            // grade_level and created_at
            $query->orderBy('enrollments.' . $sortBy, $sortDir);
        }

        // paginate and append query strings
        $applications = $query->paginate(10)->withQueryString();

        return view('users.registrar.applications-list', compact('applications'));
    }

    public function show(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'submitted', 403, 'This application is no longer pending.');

        $enrollment->load(['studentProfile', 'educationalBackground', 'payment']);

        return view('users.registrar.applications-show', compact('enrollment'));
    }

    public function admit(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'submitted', 400, 'Invalid application status.');

        $enrollment->update([
            'status' => 'registrar_approved',
        ]);

        return redirect()->route('registrar.applications.index')
            ->with('success', 'Student admitted and forwarded to the Cashier.');
    }

    public function deny(Request $request, Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'submitted', 400, 'Invalid application status.');

        $enrollment->update([
            'status' => 'denied', // or 'denied' based on your DB enums
        ]);

        return redirect()->route('registrar.applications.index')
            ->with('success', 'Application denied.');
    }
}