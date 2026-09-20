<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Student;
use Illuminate\Http\Request;

class SectioningController extends Controller
{
    public function index(ClassSchedule $class_schedule)
    {
        // get students currently assigned to this schedule
        $enrolledStudents = $class_schedule->students()->with('user')->get();

        // query ALL available fully enrolled students to show in a dropdown/list
        // eager-loads 'user' to display names (user->first_name, etc.)
        $availableStudents = Student::with('user')
            ->where('enrollment_status', 'enrolled') // based on Student model
            // Optional: ->where('grade_level', $some_logic)
            ->get();

        return view('users.admin.class-schedules.sectioning', compact(
            'class_schedule',
            'enrolledStudents',
            'availableStudents'
        ));
    }

    public function store(Request $request, ClassSchedule $class_schedule)
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

        $class_schedule->students()->syncWithoutDetaching($pivotData);

        return redirect()->route('admin.class-schedules.sectioning', $class_schedule->id)
            ->with('success', 'Students successfully added to the class.');
    }

    // method to drop a student from a class
    public function destroy(ClassSchedule $class_schedule, Student $student)
    {
        // detaches them from the pivot table entirely
        $class_schedule->students()->detach($student->id);

        // if keep the record but mark as dropped:
        // $class_schedule->students()->updateExistingPivot($student->id, ['status' => 'dropped']);

        return back()->with('success', 'Student removed from the schedule.');
    }
}
