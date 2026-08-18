<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.user')->name('login');
    Route::view('/admin-login', 'auth.staff')->name('staff-login');
    Route::view('/enroll', 'auth.enroll')->name('enroll');
});

Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');