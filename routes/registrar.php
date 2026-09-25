<?php

use App\Http\Controllers\Registrar\RegistrarEnrolledController;
use App\Http\Controllers\Registrar\RegistrarEnrollmentController;
use App\Models\Enrollments\Enrollment;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/registrar/dashboard');

Route::get('/dashboard', function () {
    // count of pending applciations
    $pendingCount = Enrollment::where('status', 'submitted')->count();

    // count of enrolled students
    $enrolledCount = Enrollment::where('status', 'enrolled')->count();

    return view('registrar.dashboard', compact('pendingCount', 'enrolledCount'));
})->name('dashboard');

Route::get('/enrolled', [RegistrarEnrolledController::class, 'index'])->name('enrolled.index');
Route::get('/enrolled/{enrollment}', [RegistrarEnrolledController::class, 'show'])->name('enrolled.show');

Route::get('/applications', [RegistrarEnrollmentController::class, 'index'])->name('applications.index');
Route::get('/applications/create', [RegistrarEnrollmentController::class, 'create'])->name('applications.create');
Route::get('/applications/{enrollment}', [RegistrarEnrollmentController::class, 'show'])->name('applications.show');
Route::patch('/applications/{enrollment}/admit', [RegistrarEnrollmentController::class, 'admit'])->name('applications.admit');
Route::patch('/applications/{enrollment}/deny', [RegistrarEnrollmentController::class, 'deny'])->name('applications.deny');

Route::get('/reports', function () {
    return view('registrar.reports');
})->name('reports');
