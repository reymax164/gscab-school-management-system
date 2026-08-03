<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
  return view('portals.cashier.dashboard');
})->name('dashboard');