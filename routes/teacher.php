<?php

use App\Http\Controllers\Teacher\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/teacher/dashboard');

Route::get('/dashboard', function () {
    return view('teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

Route::get('/student_list', function (Request $request) {
    $currentYear = now()->year;
    $defaultSy = "{$currentYear}-".($currentYear + 1);
    $selectedSy = $request->query('sy', $defaultSy);

    $classes = [
        (object) [
            'id' => 1,
            'grade_level' => '5',
            'subject' => (object) ['name' => 'Mathematics'],
            'students_count' => 35,
        ],
        (object) [
            'id' => 2,
            'grade_level' => '8',
            'subject' => (object) ['name' => 'Physics'],
            'students_count' => 40,
        ],
        (object) [
            'id' => 3,
            'grade_level' => '8',
            'subject' => (object) ['name' => 'Biology'],
            'students_count' => 28,
        ],
        (object) [
            'id' => 4,
            'grade_level' => '9',
            'subject' => (object) ['name' => 'Earth Science'],
            'students' => collect(array_fill(0, 30, 'student')),
        ],
    ];

    $classes = collect($classes);

    return view('teacher.students', compact('selectedSy', 'classes'));
})->name('students');

Route::get('/student_list/{id}', function ($id) {
    return view('teacher.view-student-list', compact('id'));
})->name('view-student-list.show');

Route::get('/student_grades', function () {
    return view('teacher.grades');
})->name('grades');
