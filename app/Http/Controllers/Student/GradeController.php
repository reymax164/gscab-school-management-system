<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        // identify the authenticated student
        $student = $request->user()->student;

        // determine target academic year (fallback to current real-world S.Y.)
        $currentYear = now()->year;
        $defaultSy = now()->month >= 6
            ? $currentYear.'-'.($currentYear + 1)
            : ($currentYear - 1).'-'.$currentYear;

        $sy = $request->input('sy', $defaultSy);

        // fetch the section for the selected S.Y.
        $section = $student ? $student->sections()->where('school_year', $sy)->first() : null;

        $grades = collect();
        $level = 'Grade #';

        if ($section) {
            $section->load(['classroom', 'adviser.user']);

            $level = ($section->grade_level === 'Kinder' ? 'Kindergarten' : 'Grade '.$section->grade_level).' - '.($section->classroom->name ?? 'N/A');

            // eager load nested relationships and scope grades to the authenticated student
            $slots = $section->subjectSchedules()
                ->with([
                    'subject',
                    'teacher.user',
                    'grades' => fn ($query) => $query->where('student_id', $student->id),
                ])
                ->get();

            // map standard object properties for the Blade view
            $grades = $slots->map(function ($slot) {
                $quarters = $slot->grades->keyBy('quarter');

                $q1 = $quarters->get('Q1')?->grade;
                $q2 = $quarters->get('Q2')?->grade;
                $q3 = $quarters->get('Q3')?->grade;
                $q4 = $quarters->get('Q4')?->grade;

                $scores = collect([$q1, $q2, $q3, $q4])->filter(fn ($score) => $score !== null);

                return (object) [
                    'subject' => $slot->subject->title ?? 'N/A',
                    'teacher' => ($slot->teacher->user->last_name ?? 'N/A').', '.($slot->teacher->user->first_name ?? ''),
                    'q1' => $q1,
                    'q2' => $q2,
                    'q3' => $q3,
                    'q4' => $q4,
                    'final' => $scores->isEmpty() ? null : round($scores->avg(), 2),
                ];
            });
        }

        // fetch all distinct academic years that exist in the database
        $schoolYears = Section::query()
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // failsafe: if the database is completely empty, ensure the current target S.Y. is available
        if ($schoolYears->isEmpty() || ! $schoolYears->contains($sy)) {
            $schoolYears->prepend($sy);
        }

        $activeSy = $sy;

        return view('student.grades', compact('grades', 'level', 'schoolYears', 'activeSy'));
    }
}
