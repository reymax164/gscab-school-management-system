<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('public.index');
})->name('home');

Route::get('news', function () {
  return view('public.news');
})->name('news');

Route::get('/about', function () {
  return view('public.about');
})->name('about');


Route::get('/contact', function () {
  return view('public.contact');
})->name('contact');

Route::get('/faqs', function () {
  return view('public.faqs');
})->name('faqs');

require __DIR__.'/auth.php';