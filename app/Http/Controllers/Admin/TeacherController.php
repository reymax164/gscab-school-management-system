<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $query = Teacher::with('user');

        // department filter
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // sorting
        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');

        $userFields = ['last_name', 'first_name'];

        if (in_array($sortBy, $userFields, true)) {
            $query->join('users', 'teachers.user_id', '=', 'users.id')
                ->orderBy('users.'.$sortBy, $sortDir)
                ->select('teachers.*');
        } else {
            $teacherFields = ['employee_id', 'hire_date'];
            $query->orderBy('teachers.'.(in_array($sortBy, $teacherFields, true) ? $sortBy : 'employee_id'), $sortDir);
        }

        $teachers = $query->paginate(15)->withQueryString();

        return view('admin.accounts.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('admin.accounts.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'employee_id' => ['required', 'string', 'max:255', 'unique:teachers,employee_id'],
            'department_id' => ['nullable', 'string', 'max:255'],
            'hire_date' => ['required', 'date'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $validated['employee_id'],
            'department_id' => $validated['department_id'] ?? null,
            'hire_date' => $validated['hire_date'],
        ]);

        return redirect()->route('admin.accounts.teachers.show', $teacher->id)
            ->with('success', 'Teacher account created successfully.');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load('user');

        return view('admin.accounts.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        $teacher->load('user');

        return view('admin.accounts.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            // employee_id is disabled/immutable in the edit form; validated defensively but never written below
            'employee_id' => ['sometimes', 'string', 'max:255', Rule::unique('teachers', 'employee_id')->ignore($teacher->id)],
            'department_id' => ['nullable', 'string', 'max:255'],
            'hire_date' => ['required', 'date'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $teacher->user->update($userData);

        $teacher->update([
            'department_id' => $validated['department_id'] ?? null,
            'hire_date' => $validated['hire_date'],
        ]);

        return redirect()->route('admin.accounts.teachers.show', $teacher->id)
            ->with('success', 'Teacher information updated successfully.');
    }
}
