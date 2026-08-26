<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Models\User;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
    // count enrolled students
    $studentCount = Student::where('enrollment_status', 'enrolled')->count();

    // count staff
    $staffCount = User::whereIn('user_type', ['admin', 'registrar', 'cashier', 'teacher'])->count();

    return view('users.admin.dashboard', compact('studentCount', 'staffCount'));
})->name('dashboard');

// ... [Keep the rest of your routes exactly as they are] ...
Route::get('/students', function () {
  return view('users.admin.students');
})->name('students');

Route::get('/staff', function () {
  return view('users.admin.staffs');
})->name('staffs');

Route::get('/schedules', function () {
    $schedules = collect([
        (object) [
            'id' => 1,
            'grade_level' => '10',
            'school_year' => '2025-2026',
            'room' => (object) ['name' => 'RM204'],
            'adviser' => (object) ['name' => 'Bayer, Janess'],
        ],
        (object) [
            'id' => 2,
            'grade_level' => '09',
            'school_year' => '2025-2026',
            'room' => (object) ['name' => 'RM301'],
            'adviser' => (object) ['name' => 'Dela Cruz, Juan'],
        ],
        (object) [
            'id' => 3,
            'grade_level' => '08',
            'school_year' => '2025-2026',
            // Passing null for the room to test the '??' fallback in Blade
            'room' => null, 
            'adviser' => (object) ['name' => 'Smith, John'],
        ],
    ]);

    return view('users.admin.schedules', compact('schedules'));
})->name('schedules');

Route::get('/news', function () {
  return view('users.admin.news');
})->name('news');

Route::get('/feedbacks', function () {
  return view('users.admin.feedbacks');
})->name('feedbacks');

