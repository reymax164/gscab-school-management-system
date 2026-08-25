<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollments\Enrollment;
use Illuminate\Http\Request;

class RegistrarEnrolledController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with('studentProfile')
            ->where('status', 'enrolled');

        // grade level filter
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');

        // ensures to only sort by allowed columns to prevent SQL injection
        $allowedSorts = ['created_at', 'grade_level'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $order === 'asc' ? 'asc' : 'desc');
        }

        $enrollments = $query->paginate(15)->withQueryString();

        return view('users.registrar.enrolled', compact('enrollments'));
    }

    public function show(Enrollment $enrollment)
    {
        // Ensure we are only viewing enrolled students
        abort_if($enrollment->status !== 'enrolled', 404, 'Student is not currently enrolled.');

        // Load all necessary relationships for the profile
        $enrollment->load([
            'studentProfile',
            'educationalBackground',
            'payment',
            'submittedDocuments', // Fetched via the pivot table
        ]);

        return view('users.registrar.enrolled-show', compact('enrollment'));
    }
}
