<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registrar\RegistrarEnrollmentController;
use App\Http\Controllers\Registrar\RegistrarRecordController;

Route::redirect('/', '/registrar/dashboard');

Route::get('/dashboard', function () {
    return view('users.registrar.dashboard');
})->name('dashboard');

Route::get('/records', [RegistrarRecordController::class, 'index'])->name('records.index');

Route::get('/records/{enrollment}', function ($enrollment) {
    $enrollment = \App\Models\Enrollments\Enrollment::with('studentProfile')->findOrFail($enrollment);
    return view('users.registrar.records-show', compact('enrollment'));
})->name('records.show');

Route::get('/applications', [RegistrarEnrollmentController::class, 'index'])->name('applications.index');
Route::get('/applications/create', [RegistrarEnrollmentController::class, 'create'])->name('applications.create');
Route::get('/applications/{enrollment}', [RegistrarEnrollmentController::class, 'show'])->name('applications.show');
Route::patch('/applications/{enrollment}/admit', [RegistrarEnrollmentController::class, 'admit'])->name('applications.admit');
Route::patch('/applications/{enrollment}/deny', [RegistrarEnrollmentController::class, 'deny'])->name('applications.deny');

Route::get('/reports', function () {
    return view('users.registrar.reports');
})->name('reports');