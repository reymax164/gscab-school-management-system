<?php

use Illuminate\Support\Facades\Route;

// student
Route::get('/dashboard', function () {
  return view('portals.student.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
  return view('portals.student.schedule');
})->name('schedule');

Route::get('/grades', function () {
  return view('portals.student.grades');
})->name('grades');

Route::get('/balance', function () {
  return view('portals.student.balance');
})->name('balance');

Route::get('/feedback', function () {
  return view('portals.student.feedback');
})->name('feedback');

Route::get('/logout', function () {
  return view('portals.student.logout');
})->name('logout');