<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
  // dummy data
  $tableColumns = ['Student Name', 'LRN', 'Grade', 'Amount', 'Payment Method', 'Date'];
  
  $paymentRows = [
      ['Christopher Evangelio', '107440090041', 'Grade 10', '₱5,000.00', 'Bank Transfer', '2026-05-14'],
      ['Juan Dela Cruz', '107440090042', 'Grade 7', '₱7,500.00', 'Bank Transfer', '2026-05-15'],
      ['Ricardo Dalisay', '107440090067', 'Grade 10', '₱7,000.00', 'Bank Transfer', '2026-05-16'],
      ['Ivan Reyes', '107440090193', 'Grade 6', '₱5,000.00', 'Cash', '2026-05-20'],
      ['Joy Bautista', '107440090104', 'Grade 8', '₱5,000.00', 'Cash', '2026-05-17'],
  ];

  return view('users.cashier.dashboard', compact('tableColumns', 'paymentRows'));
})->name('dashboard');