<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('users.admin.dashboard');
})->name('dashboard');

Route::get('/students', function () {
  return view('users.admin.students');
})->name('students');

Route::get('/staff', function () {
  return view('users.admin.staffs');
})->name('staffs');

Route::get('/schedules', function () {
  return view('users.admin.schedules');
})->name('schedules');

Route::get('/news', function () {
  return view('users.admin.news');
})->name('news');

Route::get('/feedbacks', function () {
  return view('users.admin.feedbacks');
})->name('feedbacks');