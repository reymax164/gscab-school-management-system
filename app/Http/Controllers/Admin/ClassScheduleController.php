<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassScheduleRequest;
use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relationships to prevent N+1 queries
        $query = ClassSchedule::with(['classroom', 'adviser.user']);

        if ($request->filled('sy')) {
            $query->where('academic_year', $request->sy);
        }

        $schedules = $query->latest()->paginate(15)->withQueryString();

        $academicYears = ClassSchedule::query()
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('users.admin.class-schedules.index', compact('schedules', 'academicYears'));
    }

    public function create()
    {
        return view('users.admin.class-schedules.create', [
            'subjects' => $this->subjectOptions(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
        ]);
    }

    public function show(ClassSchedule $classSchedule)
    {
        $classSchedule->load(['classroom', 'adviser.user', 'subjectSchedules.subject', 'subjectSchedules.teacher.user', 'subjectSchedules.classroom']);

        return view('users.admin.class-schedules.show', compact('classSchedule'));
    }

    public function store(StoreClassScheduleRequest $request)
    {
        $validated = $request->validated();

        $classSchedule = ClassSchedule::create([
            'grade_level' => $validated['grade_level'],
            'adviser_id' => $validated['adviser_id'],
            'academic_year' => $validated['academic_year'],
            'classroom_id' => $validated['subject_schedules'][0]['classroom_id'],
        ]);

        $classSchedule->subjectSchedules()->createMany($validated['subject_schedules']);

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule created successfully.');
    }

    public function edit(ClassSchedule $classSchedule)
    {
        $classSchedule->load('subjectSchedules');

        return view('users.admin.class-schedules.edit', [
            'classSchedule' => $classSchedule,
            'subjects' => $this->subjectOptions(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
        ]);
    }

    public function update(StoreClassScheduleRequest $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validated();

        $classSchedule->update([
            'grade_level' => $validated['grade_level'],
            'adviser_id' => $validated['adviser_id'],
            'academic_year' => $validated['academic_year'],
            'classroom_id' => $validated['subject_schedules'][0]['classroom_id'],
        ]);

        $classSchedule->subjectSchedules()->delete();
        $classSchedule->subjectSchedules()->createMany($validated['subject_schedules']);

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule deleted successfully.');
    }

    /**
     * @return array<int, array{id: int, label: string}>
     */
    private function subjectOptions(): array
    {
        return Subject::orderBy('title')->get()
            ->map(fn (Subject $subject) => ['id' => $subject->id, 'label' => $subject->title])
            ->values()->all();
    }

    /**
     * @return array<int, array{id: int, label: string}>
     */
    private function teacherOptions(): array
    {
        return Teacher::with('user')->get()
            ->map(fn (Teacher $teacher) => ['id' => $teacher->id, 'label' => $teacher->user->last_name.', '.$teacher->user->first_name])
            ->values()->all();
    }

    /**
     * @return array<int, array{id: int, label: string}>
     */
    private function classroomOptions(): array
    {
        return Classroom::orderBy('name')->get()
            ->map(fn (Classroom $classroom) => ['id' => $classroom->id, 'label' => $classroom->name])
            ->values()->all();
    }
}
