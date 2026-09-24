<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\ScheduleController;

Route::get('/login', function () {
    return 'Please submit your login form here.';
})->name('login');

Route::redirect('/', '/student/dashboard');

Route::get('/dashboard', function () {
    return view('users.student.dashboard');
})->name('dashboard');

// schedule route
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

Route::get('/grades', function () {
    return view('users.student.grades');
})->name('grades');

Route::get('/balance', function () {
    return view('users.student.balance');
})->name('balance');

Route::get('/feedback', function () {
    return view('users.student.feedback');
})->name('feedback');
