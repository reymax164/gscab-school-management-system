<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CashierEnrollmentController extends Controller
{
    public function index()
    {
        $pending = Enrollment::pendingCashier()->get();
        return view('users.cashier.balances', compact('pending'));
    }

    public function processPayment(Request $request, Enrollment $enrollment)
    {
        // Update the payment spoke record
        $enrollment->payment()->update([
            'payment_status' => 'paid',
            'or_number' => $request->or_number,
            'verified_by' => Auth::id(),
        ]);

        // Update the central hub status
        $enrollment->update(['status' => 'enrolled']);

        return back()->with('success', 'Payment verified. Student is officially enrolled.');
    }

// Inside CashierEnrollmentController.php
public function finalizeEnrollment(Request $request, Enrollment $enrollment)
{
    DB::transaction(function () use ($enrollment) {
        // 1. Update the transactional enrollment hub status
        $enrollment->update(['status' => 'enrolled']);

        // 2. Option B: Create or update the permanent Student record
        Student::updateOrCreate(
            ['user_id' => $enrollment->user_id], 
            [
                // Assuming LRN was collected and stored in the studentProfile spoke
                'lrn' => $enrollment->studentProfile->lrn ?? $request->lrn, 
                'grade_level' => $enrollment->grade_level,
                'enrollment_status' => 'enrolled',
            ]
        );

        // 3. Update the payment spoke
        $enrollment->payment()->update([
            'payment_status' => 'verified',
            // 'verified_by' => auth()->id(),
        ]);
    });

    return back()->with('success', 'Student is officially enrolled!');
}
}
