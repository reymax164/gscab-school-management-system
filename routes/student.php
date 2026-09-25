<?php

use App\Http\Controllers\Student\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return 'Please submit your login form here.';
})->name('login');

Route::redirect('/', '/student/dashboard');

Route::get('/dashboard', function () {
    return view('student.dashboard');
})->name('dashboard');

// schedule route
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

Route::get('/grades', function () {
    return view('student.grades');
})->name('grades');

Route::get('/balance', function () {
    return view('student.balance');
})->name('balance');

Route::get('/feedback', function () {
    return view('student.feedback');
})->name('feedback');
