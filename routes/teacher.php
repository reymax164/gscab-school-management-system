<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
  return view('portals.teacher.dashboard');
})->name('dashboard');