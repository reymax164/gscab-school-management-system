<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacher;

        $sections = $teacher
            ? Section::forTeacher($teacher)
                ->with([
                    'classroom',
                    'adviser.user',
                    'subjectSchedules' => fn ($query) => $query->where('teacher_id', $teacher->id)->with('subject'),
                ])
                ->latest()
                ->get()
            : collect();

        return view('teacher.students.index', compact('sections'));
    }

    public function show(Request $request, Section $section): View
    {
        $teacher = $request->user()->teacher;

        abort_if(! $teacher || ! $section->hasTeacher($teacher), 403);

        $isAdviser = $section->adviser_id === $teacher->id;

        $subjects = $section->subjectSchedules()
            ->where('teacher_id', $teacher->id)
            ->with('subject')
            ->get()
            ->pluck('subject.title')
            ->unique();

        $students = $section->students()
            ->with(['user', 'enrollment.studentProfile'])
            ->get()
            ->sortBy(fn ($student) => $student->user->last_name)
            ->values();

        $section->load(['classroom', 'adviser.user']);

        return view('teacher.students.show', compact('section', 'students', 'subjects', 'isAdviser'));
    }
}
