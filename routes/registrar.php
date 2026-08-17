<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'dashboard');

Route::get('/dashboard', function () {
  return view('users.registrar.dashboard');
})->name('dashboard');

Route::get('/records', function () {
  return view('users.registrar.records');
})->name('records');

Route::get('/applications', function () {
  return view('users.registrar.applications');
})->name('applications');

Route::get('/finalization', function () {
  return view('users.registrar.finalization');
})->name('finalization');

Route::get('/reports', function () {
  return view('users.registrar.reports');
})->name('reports');

