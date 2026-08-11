<?php

use Illuminate\Support\Facades\Route;

// student
Route::redirect('/student', 'student.dashboard');
Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('users.student.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
  return view('users.student.schedule');
})->name('schedule');

Route::get('/grades', function () {
  return view('users.student.grades');
})->name('grades');

Route::get('/balance', function () {
    return view('users.student.balance',
    // dummy data
    [
        'totalBalance' => 32500.50,
        
        'fees' => [
            (object) ['type' => 'Tuition Fee', 'amount' => 25000.00],
            (object) ['type' => 'Miscellaneous', 'amount' => 5500.00],
            (object) ['type' => 'Lab Fee', 'amount' => 2000.50],
        ],

        'dueDates' => [
            (object) ['formatted_date' => 'Sep 15, 2026', 'amount' => 10000.00],
            (object) ['formatted_date' => 'Oct 15, 2026', 'amount' => 10000.00],
        ],

        'transactions' => [
            (object) ['type' => 'Downpayment', 'date' => 'Aug 05, 2026', 'amount' => 5000.00],
        ],
    ]);
})->name('balance');

Route::get('/feedback', function () {
  return view('users.student.feedback');
})->name('feedback');