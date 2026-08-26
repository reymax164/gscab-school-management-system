<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassScheduleRequest;
use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\Teacher;

class ClassScheduleController extends Controller
{
    public function index()
    {
        // Eager load relationships to prevent N+1 queries
        $schedules = ClassSchedule::with(['subject', 'teacher', 'classroom'])
            ->latest()
            ->paginate(15);

        return view('users.admin.class-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('title')->get();
        $teachers = Teacher::with('user')->get(); // Assuming Teacher belongsTo User for name
        $classrooms = Classroom::orderBy('name')->get();

        return view('users.admin.class-schedules.create', compact('subjects', 'teachers', 'classrooms'));
    }

    public function store(StoreClassScheduleRequest $request)
    {
        ClassSchedule::create($request->validated());

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule created successfully.');
    }

    public function edit(ClassSchedule $classSchedule)
    {
        $subjects = Subject::orderBy('title')->get();
        $teachers = Teacher::with('user')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('users.admin.class-schedules.edit', compact('classSchedule', 'subjects', 'teachers', 'classrooms'));
    }

    public function update(StoreClassScheduleRequest $request, ClassSchedule $classSchedule)
    {
        $classSchedule->update($request->validated());

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();

        return redirect()->route('admin.class-schedules.index')
            ->with('success', 'Class schedule deleted successfully.');
    }
}
