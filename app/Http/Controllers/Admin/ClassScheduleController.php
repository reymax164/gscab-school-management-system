<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassScheduleRequest;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['classroom', 'adviser.user']);

        // fetch distinct academic years for the filter dropdown
        $academicYears = ClassSchedule::query()
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        // determine the target school year for warning system.
        // default to the requested 'sy', or if none is selected, the latest available academic year.
        $targetSy = $request->filled('sy') ? $request->sy : $academicYears->first();

        // apply the filter if requested
        if ($request->filled('sy')) {
            $query->where('academic_year', $request->sy);
        }

        $schedules = $query->latest()->paginate(15)->withQueryString();

        // calculate missing grade levels for the target school year
        $missingGrades = [];
        if ($targetSy) {
            // define the standard grade levels
            $standardGrades = collect(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);
            
            // pluck the grade levels that currently have a schedule for the target S.Y.
            $existingGrades = ClassSchedule::where('academic_year', $targetSy)
                ->pluck('grade_level')
                ->unique();
                
            // diff() to find what is missing
            $missingGrades = $standardGrades->diff($existingGrades)->values()->all();
        }

        return view('users.admin.class-schedules.index', compact(
            'schedules', 
            'academicYears', 
            'targetSy', 
            'missingGrades'
        ));
    }

    public function create()
    {
        return view('users.admin.class-schedules.create', [
            'subjects' => $this->subjectOptions(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
            'existingCounts' => $this->getExistingScheduleCounts(),
            'adviserCounts' => $this->getAdviserCounts(),
        ]);
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

        // --- auto-sectioning ---
        // check if this is the ONLY schedule for this grade and S.Y.
        $scheduleCount = ClassSchedule::where('grade_level', $validated['grade_level'])
            ->where('academic_year', $validated['academic_year'])
            ->count();

        if ($scheduleCount === 1) {
            // find all enrolled students for this grade level who don't have a schedule for this S.Y. yet
            $studentIds = Student::where('enrollment_status', 'enrolled')
                ->where('grade_level', $validated['grade_level'])
                ->whereDoesntHave('classSchedules', function ($query) use ($validated) {
                    $query->where('academic_year', $validated['academic_year']);
                })
                ->pluck('id');
                
            if ($studentIds->isNotEmpty()) {
                $pivotData = [];
                foreach ($studentIds as $id) {
                    $pivotData[$id] = ['status' => 'enrolled'];
                }
                $classSchedule->students()->syncWithoutDetaching($pivotData);
            }
        }

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
            'existingCounts' => $this->getExistingScheduleCounts($classSchedule->id),
            'adviserCounts' => $this->getAdviserCounts($classSchedule->id),
        ]);
    }

    public function show(ClassSchedule $classSchedule)
    {
        $classSchedule->load(['classroom', 'adviser.user', 'subjectSchedules.subject', 'subjectSchedules.teacher.user', 'subjectSchedules.classroom']);

        return view('users.admin.class-schedules.show', compact('classSchedule'));
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

    /**
     * Get a mapped array of existing schedules: [ '2026-2027' => [ '7' => 1, '8' => 2 ] ]
     */
    private function getExistingScheduleCounts(?int $excludeId = null): array
    {
        $query = ClassSchedule::query();
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->select('academic_year', 'grade_level')
            ->get()
            ->groupBy('academic_year')
            ->map(function ($yearGroup) {
                return $yearGroup->groupBy('grade_level')->map->count();
            })
            ->toArray();
    }

    /**
     * Get a mapped array of existing adviser assignments: [ '2026-2027' => [ 'teacher_id' => 1 ] ]
     */
    private function getAdviserCounts(?int $excludeId = null): array
    {
        $query = ClassSchedule::query();
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->select('academic_year', 'adviser_id')
            ->get()
            ->groupBy('academic_year')
            ->map(function ($yearGroup) {
                return $yearGroup->groupBy('adviser_id')->map->count();
            })
            ->toArray();
    }
}
