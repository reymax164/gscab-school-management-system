<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class SectioningController extends Controller
{
    public function index(Section $section)
    {
        // get students currently assigned to this schedule
        $enrolledStudents = $section->students()->with('user')->get();

        // query ALL available fully enrolled students to show in a dropdown/list
        // eager-loads 'user' to display names (user->first_name, etc.)
        $availableStudents = Student::with('user')
            ->where('enrollment_status', 'enrolled') // based on Student model
            // Optional: ->where('grade_level', $some_logic)
            ->get();

        return view('admin.sections.sectioning', compact(
            'section',
            'enrolledStudents',
            'availableStudents'
        ));
    }

    public function store(Request $request, Section $section)
    {
        // expecting an array of student IDs from a multi-select or checkboxes
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        // syncWithoutDetaching adds the new students to the pivot table
        // without removing the ones already there.
        // pass the default pivot 'status' => 'enrolled'

        $pivotData = [];
        foreach ($request->student_ids as $id) {
            $pivotData[$id] = ['status' => 'enrolled'];
        }

        $section->students()->syncWithoutDetaching($pivotData);

        return redirect()->route('admin.sections.sectioning', $section->id)
            ->with('success', 'Students successfully added to the class.');
    }

    // method to drop a student from a class
    public function destroy(Section $section, Student $student)
    {
        // detaches them from the pivot table entirely
        $section->students()->detach($student->id);

        // if keep the record but mark as dropped:
        // $section->students()->updateExistingPivot($student->id, ['status' => 'dropped']);

        return back()->with('success', 'Student removed from the schedule.');
    }
}
