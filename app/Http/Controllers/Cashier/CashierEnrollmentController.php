<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Enrollments\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['studentProfile', 'payment'])
            ->where('status', 'registrar_approved') // retrives registrar admitted applications
            ->join('student_profiles', 'enrollments.id', '=', 'student_profiles.enrollment_id')
            ->select('enrollments.*'); 

        // sorting options
        $sortBy = $request->input('sort_by', 'last_name'); 
        $sortDir = $request->input('sort_dir', 'asc'); 

        $profileFields = ['last_name', 'first_name', 'lrn'];

        if (in_array($sortBy, $profileFields)) {
            $query->orderBy('student_profiles.' . $sortBy, $sortDir);
        } else {
            $query->orderBy('enrollments.' . $sortBy, $sortDir);
        }

        $applications = $query->paginate(10)->withQueryString();

        return view('users.cashier.enrollment', compact('applications'));
    }
 

    public function show(Enrollment $enrollment)
    {
        abort_if($enrollment->status !== 'registrar_approved', 403, 'Invalid status for payment.');
        
        $enrollment->load(['studentProfile', 'payment']);
        
        return view('users.cashier.enrollment-show', compact('enrollment'));
    }

    public function processPayment(Request $request, Enrollment $enrollment)
    {
        // validate the input
        $request->validate([
            'payment_method' => 'required|string|max:50',
            'or_number'      => 'required|string|max:255',
        ]);

        // update the Payment Record with the new method
        $enrollment->payment()->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'or_number'      => $request->or_number,
            'verified_by'    => Auth::id(), 
        ]);

        // advance to enrolled status
        $enrollment->update([
            'status' => 'enrolled',
        ]);

        return redirect()->route('cashier.enrollment.index')
            ->with('success', 'Payment processed successfully! The student is now officially enrolled.');
    }
}