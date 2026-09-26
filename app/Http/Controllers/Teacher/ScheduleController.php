<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacher;

        $sections = $teacher
            ? Section::forTeacher($teacher)->with(['classroom', 'adviser.user'])->latest()->get()
            : collect();

        return view('teacher.schedules.index', compact('sections'));
    }

    public function show(Request $request, Section $section): View
    {
        $teacher = $request->user()->teacher;

        abort_if(! $teacher || ! $section->hasTeacher($teacher), 403);

        $isAdviser = $section->adviser_id === $teacher->id;

        $subjectSchedules = $section->subjectSchedules()
            ->with(['subject', 'teacher.user', 'classroom'])
            ->when(! $isAdviser, fn ($query) => $query->where('teacher_id', $teacher->id))
            ->orderBy('start_time')
            ->get();

        $section->load(['classroom', 'adviser.user']);

        return view('teacher.schedules.show', compact('section', 'subjectSchedules', 'isAdviser'));
    }
}
