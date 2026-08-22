<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registrar\RegistrarEnrollmentController;

Route::redirect('/', '/registrar/dashboard');

Route::get('/dashboard', function () {
    return view('users.registrar.dashboard');
})->name('dashboard');

Route::get('/records', function () {
    return view('users.registrar.records');
})->name('records');

Route::get('/applications', function () {
    return view('users.registrar.applications');
})->name('applications');

// --- enrollment processing ---
// Route::get('/applications', [RegistrarEnrollmentController::class, 'index'])->name('applications');
// Route::get('/applications/{enrollment}', [RegistrarEnrollmentController::class, 'show'])->name('applications.show');
// Route::patch('/applications/{enrollment}/approve', [RegistrarEnrollmentController::class, 'approve'])->name('applications.approve');
// Route::patch('/applications/{enrollment}/reject', [RegistrarEnrollmentController::class, 'reject'])->name('applications.reject');

Route::get('/finalization', function () {
    return view('users.registrar.final-verification');
})->name('final-verification');

Route::get('/reports', function () {
    return view('users.registrar.reports');
})->name('reports');