<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return 'Please submit your login form here.';
})->name('login');

Route::get('/', function () {
  return view('guest.index');
})->name('home');

Route::get('news', function () {
  return view('guest.news');
})->name('news');

Route::get('/about', function () {
  return view('guest.about');
})->name('about');

Route::get('/contact', function () {
  return view('guest.contact');
})->name('contact');

Route::get('/faqs', function () {
  return view('guest.faqs');
})->name('faqs');

require __DIR__.'/auth.php';