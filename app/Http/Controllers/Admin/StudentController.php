<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Student;
use App\Models\ClassSchedule;
use App\Models\Enrollments\Enrollment;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with('studentProfile')
            ->where('status', 'enrolled')
            ->join('student_profiles', 'enrollments.id', '=', 'student_profiles.enrollment_id')
            ->select('enrollments.*');

        // grade level filter
        if ($request->filled('grade_level')) {
            $query->where('enrollments.grade_level', $request->grade_level);
        }

        // sorting
        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');

        $profileFields = ['last_name', 'first_name', 'lrn'];

        if (in_array($sortBy, $profileFields)) {
            $query->orderBy('student_profiles.'.$sortBy, $sortDir);
        } else {
            $query->orderBy('enrollments.'.$sortBy, $sortDir);
        }

        $students = $query->paginate(15)->withQueryString();

        return view('users.admin.students.index', compact('students'));
    }

    public function show(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'enrolled', 404, 'Student is not currently enrolled.');

        $enrollment->load(['studentProfile', 'educationalBackground', 'payment', 'user']);

        return view('users.admin.students.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'enrolled', 404, 'Student is not currently enrolled.');

        $enrollment->load(['studentProfile', 'user']);

        return view('users.admin.students.edit', compact('enrollment'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'enrolled', 404, 'Student is not currently enrolled.');

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($enrollment->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'grade_level' => ['required', 'string'],
            'student_status' => ['required', 'in:new,existing,transferee'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'house_no' => ['nullable', 'string', 'max:255'],
            'sitio_subdivision' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:20'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $profile = $enrollment->studentProfile;

        // centralize user account creation & updates
        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'lrn' => $profile->lrn, // maps LRN to the users table
            'role' => 'student',
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        if ($enrollment->user) {
            // update existing user account
            $enrollment->user->update($userData);
            $userId = $enrollment->user_id;
        } else {
            // create a brand new user account for guest applicants
            if (empty($validated['password'])) {
                $userData['password'] = Hash::make('password'); // failsafe fallback
            }
            $user = User::create($userData);
            $userId = $user->id;
            
            // link the new user to the enrollment hub
            $enrollment->update(['user_id' => $userId]);
        }

        // update Enrollment Details
        $enrollment->update([
            'grade_level' => $validated['grade_level'],
            'student_status' => $validated['student_status'],
        ]);

        // update the temporary Student Profile Spoke
        $guardianDetails = $profile->guardian_details ?? [];
        $guardianDetails['name'] = $validated['guardian_name'] ?? null;

        $contactPerson = $profile->contact_person ?? [];
        $contactPerson['number'] = $validated['contact_number'] ?? null;

        $profile->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'age' => $validated['age'],
            'birthplace' => $validated['birthplace'] ?? null,
            'house_no' => $validated['house_no'] ?? null,
            'sitio_subdivision' => $validated['sitio_subdivision'] ?? null,
            'barangay' => $validated['barangay'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'guardian_details' => $guardianDetails,
            'contact_person' => $contactPerson,
        ]);

        // create/update the official student model 
        $student = Student::updateOrCreate(
            ['user_id' => $userId],
            [
                'grade_level' => $validated['grade_level'],
                'enrollment_status' => 'enrolled',
            ]
        );

        // --- auto-section ---
        // calculate the current academic year
        $currentYear = now()->year;
        $academicYear = now()->month >= 6 
            ? $currentYear . '-' . ($currentYear + 1) 
            : ($currentYear - 1) . '-' . $currentYear;

        // check if there is exactly one schedule for a grade level in the active S.Y.
        $schedules = ClassSchedule::where('grade_level', $student->grade_level)
            ->where('academic_year', $academicYear)
            ->get();

        if ($schedules->count() === 1) {
            $schedules->first()->students()->syncWithoutDetaching([
                $student->id => ['status' => 'enrolled']
            ]);
        }

        return redirect()->route('admin.students.show', $enrollment->id)
            ->with('success', 'Student information updated successfully.');
    }
}
