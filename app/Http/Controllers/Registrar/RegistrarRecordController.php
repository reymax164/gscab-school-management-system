<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrarRecordController extends Controller
{
public function index(Request $request)
    {
        $query = \App\Models\Enrollments\Enrollment::with('studentProfile')
            ->where('status', 'enrolled');

        // grade level filter
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');

        // ensures to only sort by allowed columns to prevent SQL injection
        $allowedSorts = ['created_at', 'grade_level'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $order === 'asc' ? 'asc' : 'desc');
        }

        $enrollments = $query->paginate(15)->withQueryString();

        return view('users.registrar.records', compact('enrollments'));
    }
}
