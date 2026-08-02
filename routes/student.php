<?php

use Illuminate\Support\Facades\Route;

// student
Route::get('/dashboard', function () {
  return view('user_student.dashboard');
});