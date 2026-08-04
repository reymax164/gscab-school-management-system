<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('portals.registrar.dashboard');
})->name('dashboard');

Route::get('/records', function () {
  return view('portals.registrar.records');
})->name('records');

Route::get('/admisisons', function () {
  return view('portals.registrar.admissions');
})->name('admissions');

Route::get('/reports', function () {
  return view('portals.registrar.reports');
})->name('reports');

