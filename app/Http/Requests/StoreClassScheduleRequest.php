<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class StoreClassScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware already handles authorization
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'days' => 'required|string|max:50', // e.g., 'MWF', 'TTH'
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $start = $this->start_time;
                $end = $this->end_time;
                $days = $this->days;
                $semester = $this->semester;
                $year = $this->academic_year;

                // Standard time overlap formula: (NewStart < ExistingEnd) AND (NewEnd > ExistingStart)
                $conflictQuery = DB::table('class_schedules')
                    ->where('days', $days)
                    ->where('semester', $semester)
                    ->where('academic_year', $year)
                    ->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);

                // If updating an existing schedule, ignore its own ID
                if ($this->route('class_schedule')) {
                    $conflictQuery->where('id', '!=', $this->route('class_schedule')->id);
                }

                // 1. Check Teacher Conflict
                $teacherConflict = (clone $conflictQuery)->where('teacher_id', $this->teacher_id)->exists();
                if ($teacherConflict) {
                    $validator->errors()->add('teacher_id', 'This teacher is already scheduled for another class during this time.');
                }

                // 2. Check Classroom Conflict
                $roomConflict = (clone $conflictQuery)->where('classroom_id', $this->classroom_id)->exists();
                if ($roomConflict) {
                    $validator->errors()->add('classroom_id', 'This classroom is already booked during this time.');
                }
            },
        ];
    }
}
