<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
  return view('portals.admin.dashboard');
})->name('dashboard');