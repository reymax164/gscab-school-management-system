<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // identify the authenticated student
        $student = $request->user()->student;

        // determine target academic year (fallback to current real-world S.Y.)
        $currentYear = now()->year;
        $defaultSy = now()->month >= 6
            ? $currentYear.'-'.($currentYear + 1)
            : ($currentYear - 1).'-'.$currentYear;

        $sy = $request->input('sy', $defaultSy);

        // determine target Day
        $requestedDay = $request->input('day', 'Today');
        $dayMap = [
            'Monday' => 'Mon', 'Tuesday' => 'Tue', 'Wednesday' => 'Wed',
            'Thursday' => 'Thu', 'Friday' => 'Fri', 'Saturday' => 'Sat',
        ];

        // if "Today", grab the current day abbreviation (e.g., 'Mon').
        // otherwise, map the requested full name to the DB abbreviation.
        $targetDay = $requestedDay === 'Today' ? now()->format('D') : ($dayMap[$requestedDay] ?? null);

        // fetch the schedule block
        $classSchedule = $student ? $student->classSchedules()->where('school_year', $sy)->first() : null;

        $schedules = collect();

        // query and map timeslots to avoid N+1 and format data cleanly for the view
        if ($classSchedule) {
            // eager load nested relationships
            $slots = $classSchedule->subjectSchedules()
                ->with(['subject', 'teacher.user', 'classroom'])
                ->orderBy('start_time')
                ->get();

            // filter by selected day
            if ($targetDay) {
                $slots = $slots->filter(fn ($slot) => str_contains($slot->days, $targetDay));
            }

            // map standard object properties for the Blade View
            $schedules = $slots->map(function ($slot) {
                return (object) [
                    'subject' => $slot->subject->title ?? 'N/A',
                    'day' => $slot->days,
                    'time' => Carbon::parse($slot->start_time)->format('g:i A').' - '.Carbon::parse($slot->end_time)->format('g:i A'),
                    'room' => $slot->classroom->name ?? 'N/A',
                    'teacher' => ($slot->teacher->user->last_name ?? 'N/A').', '.($slot->teacher->user->first_name ?? ''),
                ];
            });
        }

        // fetch all distinct academic years that exist in the database
        $schoolYears = ClassSchedule::query()
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // failsafe: if the database is completely empty, ensure the current target S.Y. is available
        if ($schoolYears->isEmpty() || ! $schoolYears->contains($sy)) {
            $schoolYears->prepend($sy);
        }

        return view('student.schedule', [
            'schedules' => $schedules,
            'grade' => $student->grade_level ?? 'N/A',
            'activeSy' => $sy,
            'activeDay' => $requestedDay,
            'schoolYears' => $schoolYears,
        ]);
    }
}
