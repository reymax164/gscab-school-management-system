<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollments\DocumentRequirement;
use App\Models\Enrollments\Enrollment;
use Illuminate\Http\Request;

class RegistrarEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with('studentProfile')
            ->where('status', 'submitted')
            ->join('student_profiles', 'enrollments.id', '=', 'student_profiles.enrollment_id')
            ->select('enrollments.*');

        // Handle Grade Level Filter
        if ($request->filled('grade_level')) {
            $query->where('enrollments.grade_level', $request->grade_level);
        }

        // Handle Sorting
        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');

        $profileFields = ['last_name', 'first_name', 'lrn'];

        if (in_array($sortBy, $profileFields)) {
            $query->orderBy('student_profiles.'.$sortBy, $sortDir);
        } else {
            // grade_level and created_at
            $query->orderBy('enrollments.'.$sortBy, $sortDir);
        }

        // paginate and append query strings
        $applications = $query->paginate(10)->withQueryString();

        return view('registrar.applications.index', compact('applications'));
    }

    public function show(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'submitted', 403, 'This application is no longer pending.');

        $enrollment->load(['studentProfile', 'educationalBackground', 'payment']);
        $requirements = DocumentRequirement::where('is_active', true)->get();

        return view('registrar.applications.show', compact('enrollment', 'requirements'));
    }

    public function admit(Request $request, Enrollment $enrollment)
    {
        // ensure the application is actually pending
        abort_if($enrollment->status !== 'submitted', 403, 'Application cannot be modified.');

        // validate the incoming document IDs
        $request->validate([
            'submitted_documents' => 'required|array',
            'submitted_documents.*' => 'exists:document_requirements,id',
        ]);

        // save the checklist to the database
        $enrollment->submittedDocuments()->sync($request->submitted_documents);

        // advance the status so the Cashier can see it
        $enrollment->update([
            'status' => 'registrar_approved',
        ]);

        return redirect()->route('registrar.applications.index')
            ->with('success', 'Student admitted and documents successfully verified.');
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
