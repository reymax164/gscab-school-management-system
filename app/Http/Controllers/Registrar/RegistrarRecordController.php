<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrarRecordController extends Controller
{
    public function index(Request $request)
    {
        // Start with only officially enrolled students
        $query = \App\Models\Enrollments\Enrollment::with('studentProfile')
            ->where('status', 'enrolled');

        // Apply grade level filter if selected
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        $enrollments = $query->latest()->paginate(15);

        return view('users.registrar.records', compact('enrollments'));
    }
}
