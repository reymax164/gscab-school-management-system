<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('portals.teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
  return view('portals.teacher.schedule');
})->name('schedule');

Route::get('/student_list', function () {
  return view('portals.teacher.student_list');
})->name('student_list');

Route::get('/student_grades', function () {
  return view('portals.teacher.student_grades');
})->name('grades');