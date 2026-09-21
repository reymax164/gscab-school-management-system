<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use Illuminate\Support\Facades\Route;

// guest routes (views)
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.student')->name('student-login');
    Route::view('/staff-login', 'auth.staff')->name('staff-login');
    Route::view('/enroll', 'enrollment.form')->name('enroll');
});

// student auth
Route::post('/login', [StudentAuthController::class, 'authenticate'])->name('student.authenticate');

// staff auth
Route::post('/staff-login', [StaffAuthController::class, 'authenticate'])->name('staff.authenticate');

// logout
Route::post('/logout', LogoutController::class)->name('logout');
