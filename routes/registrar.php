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

Route::get('/applications', [RegistrarEnrollmentController::class, 'index'])->name('applications.index');
Route::get('/applications/create', [RegistrarEnrollmentController::class, 'create'])->name('applications.create');
Route::get('/applications/{enrollment}', [RegistrarEnrollmentController::class, 'show'])->name('applications.show');
Route::patch('/applications/{enrollment}/admit', [RegistrarEnrollmentController::class, 'admit'])->name('applications.admit');
Route::patch('/applications/{enrollment}/deny', [RegistrarEnrollmentController::class, 'deny'])->name('applications.deny');

Route::get('/finalization', function () {
    return view('users.registrar.final-verification');
})->name('final-verification');

Route::get('/reports', function () {
    return view('users.registrar.reports');
})->name('reports');