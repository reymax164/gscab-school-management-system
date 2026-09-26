<?php

use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Teacher\StudentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/teacher/dashboard');

Route::get('/dashboard', function () {
    return view('teacher.dashboard');
})->name('dashboard');

Route::prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('index');
    Route::get('/{section}', [ScheduleController::class, 'show'])->name('show');
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::get('/{section}', [StudentController::class, 'show'])->name('show');
});

Route::get('/student_grades', function () {
    return view('teacher.grades');
})->name('grades');
