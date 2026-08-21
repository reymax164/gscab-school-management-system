<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Enrollments\Enrollment;
use Illuminate\Http\Request;

class RegistrarEnrollmentController extends Controller
{
    public function index()
    {
        $pending = Enrollment::pendingRegistrar()->get();
        return view('users.registrar.applications', compact('pending'));
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'registrar_approved']);
        
        return back()->with('success', 'Student approved. Forwarded to Cashier.');
    }
}
