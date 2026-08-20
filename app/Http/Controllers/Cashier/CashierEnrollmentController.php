<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Student;
use App\Models\Enrollment;

class CashierEnrollmentController extends Controller
{
    public function index()
    {
        $pending = Enrollment::pendingCashier()->get();
        // the view where the cashier sees all student balances
        return view('users.cashier.balances', compact('pending'));
    }

    public function processPayment(Request $request, Enrollment $enrollment)
    {
        // update the payment spoke record
        $enrollment->payment()->update([
            'payment_status' => 'paid',
            'or_number' => $request->or_number,
            'verified_by' => Auth::id(),
        ]);

        // update the central hub status
        $enrollment->update(['status' => 'enrolled']);

        return back()->with('success', 'Payment verified. Student is officially enrolled.');
    }

    public function finalizeEnrollment(Request $request, Enrollment $enrollment)
    {
        DB::transaction(function () use ($enrollment, $request) {
            // update the transactional enrollment hub status
            $enrollment->update(['status' => 'enrolled']);

            // create or update the permanent Student record, finalize
            Student::updateOrCreate(
                ['user_id' => $enrollment->user_id], 
                [
                    'lrn' => $enrollment->studentProfile->lrn ?? $request->lrn, 
                    'grade_level' => $enrollment->grade_level,
                    'enrollment_status' => 'enrolled',
                ]
            );

            // update the payment spoke
            $enrollment->payment()->update([
                'payment_status' => 'verified',
                // 'verified_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Student is officially enrolled!');
    }
}
