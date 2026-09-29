<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\SubjectSchedule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacher;

        $subjectSchedules = $teacher
            ? $teacher->subjectSchedules()->with(['section.classroom', 'subject'])->get()
            : collect();

        return view('teacher.grades.index', compact('subjectSchedules'));
    }

    public function show(Request $request, SubjectSchedule $subjectSchedule): View
    {
        $teacher = $request->user()->teacher;

        abort_if(! $teacher || $subjectSchedule->teacher_id !== $teacher->id, 403);

        $subjectSchedule->load(['section.classroom', 'subject']);

        $students = $subjectSchedule->section->students()
            ->with(['user', 'grades' => fn ($query) => $query->where('subject_schedule_id', $subjectSchedule->id)])
            ->get()
            ->sortBy(fn ($student) => $student->user->last_name)
            ->values();

        return view('teacher.grades.show', compact('subjectSchedule', 'students'));
    }

    public function update(Request $request, SubjectSchedule $subjectSchedule): RedirectResponse
    {
        $teacher = $request->user()->teacher;

        abort_if(! $teacher || $subjectSchedule->teacher_id !== $teacher->id, 403);

        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*' => ['array'],
            'grades.*.Q1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q4' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($validated['grades'] as $studentId => $quarters) {
            foreach ($quarters as $quarter => $grade) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_schedule_id' => $subjectSchedule->id,
                        'quarter' => $quarter,
                    ],
                    ['grade' => $grade === '' ? null : $grade]
                );
            }
        }

        return redirect()->route('teacher.grades.show', $subjectSchedule);
    }
}
