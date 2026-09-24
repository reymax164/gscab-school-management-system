<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrarReportController extends Controller
{
    public function index(Request $request)
    {
        $schoolYears = ['2026-2027', '2025-2026', '2024-2025', '2023-2024'];

        return view('users.registrar.reports', [
            'schoolYears' => $schoolYears,
            'selectedYear' => $request->query('school_year', $schoolYears[0]),
            'totalEnrollees' => 205,
            'growth' => 4.25,
            'yearlyDifference' => [
                ['year' => 2026, 'difference' =>150],
                ['year' => 2025, 'difference' => 130],
                ['year' => 2024, 'difference' => 120],
                ['year' => 2023, 'difference' => 100],
            ],
            'gradeLevels' => [
                ['grade_level' => 'Kinder', 'count' => 60],
                ['grade_level' => 'Grade 1', 'count' => 50],
                ['grade_level' => 'Grade 2', 'count' => 40],
                ['grade_level' => 'Grade 3', 'count' => 30],
                ['grade_level' => 'Grade 4', 'count' => 25],
                ['grade_level' => 'Grade 5', 'count' => 20],
                ['grade_level' => 'Grade 6', 'count' => 15],
                ['grade_level' => 'Grade 7', 'count' => 79],
                ['grade_level' => 'Grade 8', 'count' => 77],
                ['grade_level' => 'Grade 9', 'count' => 75],
                ['grade_level' => 'Grade 10', 'count' =>73],
            ],
        ]);
    }
}