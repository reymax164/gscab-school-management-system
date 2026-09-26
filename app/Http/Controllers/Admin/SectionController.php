<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with(['classroom', 'adviser.user']);

        // fetch distinct school years for the filter dropdown
        $schoolYears = Section::query()
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // determine the target school year for warning system.
        // default to the requested 'sy', or if none is selected, the latest available school year.
        $targetSy = $request->filled('sy') ? $request->sy : $schoolYears->first();

        // apply the filter if requested
        if ($request->filled('sy')) {
            $query->where('school_year', $request->sy);
        }

        $schedules = $query->latest()->paginate(15)->withQueryString();

        // calculate missing grade levels for the target school year
        $missingGrades = [];
        if ($targetSy) {
            // define the standard grade levels
            $standardGrades = collect(['Kinder', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);

            // pluck the grade levels that currently have a schedule for the target S.Y.
            $existingGrades = Section::where('school_year', $targetSy)
                ->pluck('grade_level')
                ->unique();

            // diff() to find what is missing
            $missingGrades = $standardGrades->diff($existingGrades)->values()->all();
        }

        return view('admin.sections.index', compact(
            'schedules',
            'schoolYears',
            'targetSy',
            'missingGrades'
        ));
    }

    public function create()
    {
        return view('admin.sections.create', [
            'subjects' => $this->subjectOptions(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
            'existingCounts' => $this->getExistingScheduleCounts(),
            'adviserCounts' => $this->getAdviserCounts(),
        ]);
    }

    public function store(StoreSectionRequest $request)
    {
        $validated = $request->validated();

        $section = Section::create([
            'grade_level' => $validated['grade_level'],
            'adviser_id' => $validated['adviser_id'],
            'school_year' => $validated['school_year'],
            'classroom_id' => $validated['subject_schedules'][0]['classroom_id'],
        ]);

        $section->subjectSchedules()->createMany($validated['subject_schedules']);

        // --- auto-sectioning ---
        // check if this is the ONLY schedule for this grade and S.Y.
        $scheduleCount = Section::where('grade_level', $validated['grade_level'])
            ->where('school_year', $validated['school_year'])
            ->count();

        if ($scheduleCount === 1) {
            // find all enrolled students for this grade level who don't have a schedule for this S.Y. yet
            $studentIds = Student::where('enrollment_status', 'enrolled')
                ->where('grade_level', $validated['grade_level'])
                ->whereDoesntHave('sections', function ($query) use ($validated) {
                    $query->where('school_year', $validated['school_year']);
                })
                ->pluck('id');

            if ($studentIds->isNotEmpty()) {
                $pivotData = [];
                foreach ($studentIds as $id) {
                    $pivotData[$id] = ['status' => 'enrolled'];
                }
                $section->students()->syncWithoutDetaching($pivotData);
            }
        }

        return redirect()->route('admin.sections.index')
            ->with('success', 'Class schedule created successfully.');
    }

    public function edit(Section $section)
    {
        $section->load('subjectSchedules');

        return view('admin.sections.edit', [
            'section' => $section,
            'subjects' => $this->subjectOptions(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
            'existingCounts' => $this->getExistingScheduleCounts($section->id),
            'adviserCounts' => $this->getAdviserCounts($section->id),
        ]);
    }

    public function show(Section $section)
    {
        $section->load(['classroom', 'adviser.user', 'subjectSchedules.subject', 'subjectSchedules.teacher.user', 'subjectSchedules.classroom']);

        return view('admin.sections.show', compact('section'));
    }

    public function update(StoreSectionRequest $request, Section $section)
    {
        $validated = $request->validated();

        $section->update([
            'grade_level' => $validated['grade_level'],
            'adviser_id' => $validated['adviser_id'],
            'school_year' => $validated['school_year'],
            'classroom_id' => $validated['subject_schedules'][0]['classroom_id'],
        ]);

        $section->subjectSchedules()->delete();
        $section->subjectSchedules()->createMany($validated['subject_schedules']);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
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
        $query = Section::query();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->select('school_year', 'grade_level')
            ->get()
            ->groupBy('school_year')
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
        $query = Section::query();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->select('school_year', 'adviser_id')
            ->get()
            ->groupBy('school_year')
            ->map(function ($yearGroup) {
                return $yearGroup->groupBy('adviser_id')->map->count();
            })
            ->toArray();
    }
}
