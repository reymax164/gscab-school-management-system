<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
  return view('user_admin.dashboard');
});