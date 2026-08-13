<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('users.teacher.dashboard');
})->name('dashboard');

Route::get('/schedule', function () {
  return view('users.teacher.schedule');
})->name('schedule');

Route::get('/student_list', function () {
  return view('users.teacher.student-list');
})->name('student-list');

Route::get('student_list/view', function () {
  return view('users.teacher.view-student-list');
})->name('student-list.view');

Route::get('/student_grades', function () {
  return view('users.teacher.student-grades');
})->name('grades');