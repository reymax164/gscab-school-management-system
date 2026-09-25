<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Identify the authenticated teacher
        $teacher = $request->user()->teacher;

        // determine target academic year
        $currentYear = now()->year;
        $defaultSy = now()->month >= 6
            ? $currentYear.'-'.($currentYear + 1)
            : ($currentYear - 1).'-'.$currentYear;

        $sy = $request->input('sy', $defaultSy);

        // determine target day
        $requestedDay = $request->input('day', 'Today');
        $dayMap = [
            'Monday' => 'Mon', 'Tuesday' => 'Tue', 'Wednesday' => 'Wed',
            'Thursday' => 'Thu', 'Friday' => 'Fri', 'Saturday' => 'Sat',
        ];
        $targetDay = $requestedDay === 'Today' ? now()->format('D') : ($dayMap[$requestedDay] ?? null);

        $subjectSchedules = collect();

        // query and map timeslots
        if ($teacher) {
            // eager load nested relationships and filter by S.Y. on the parent ClassSchedule
            $slots = $teacher->subjectSchedules()
                ->with(['subject', 'classroom', 'classSchedule'])
                ->whereHas('classSchedule', function ($query) use ($sy) {
                    $query->where('school_year', $sy);
                })
                ->orderBy('start_time')
                ->get();

            // filter by selected day
            if ($targetDay) {
                $slots = $slots->filter(fn ($slot) => str_contains($slot->days, $targetDay));
            }

            // Map standard object properties for the Blade View
            $subjectSchedules = $slots->map(function ($slot) {
                $gradeStr = $slot->classSchedule->grade_level === 'Kinder'
                    ? 'Kindergarten'
                    : 'Grade '.$slot->classSchedule->grade_level;

                return (object) [
                    'subject' => $slot->subject->title ?? 'N/A',
                    'grade' => $gradeStr,
                    'day' => $slot->days,
                    'time' => Carbon::parse($slot->start_time)->format('g:i A').' - '.Carbon::parse($slot->end_time)->format('g:i A'),
                    'room' => $slot->classroom->name ?? 'N/A',
                ];
            });
        }

        $schoolYears = ClassSchedule::query()
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // failsafe: if the database is completely empty, ensure the current target S.Y. is available
        if ($schoolYears->isEmpty() || ! $schoolYears->contains($sy)) {
            $schoolYears->prepend($sy);
        }

        return view('teacher.schedule', [
            'schedules' => $subjectSchedules,
            'activeSy' => $sy,
            'activeDay' => $requestedDay,
            'schoolYears' => $schoolYears,
        ]);
    }
}
