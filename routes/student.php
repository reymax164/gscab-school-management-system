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
  return view('users.student.balance');
})->name('balance');

Route::get('/feedback', function () {
  return view('users.student.feedback');
})->name('feedback');