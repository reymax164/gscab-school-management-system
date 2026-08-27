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
            'grade_level' => 'required|string|max:50',
            'adviser_id' => 'required|exists:teachers,id',
            'academic_year' => 'required|string|max:20',

            'subject_schedules' => 'required|array|min:1',
            'subject_schedules.*.subject_id' => 'required|exists:subjects,id',
            'subject_schedules.*.teacher_id' => 'required|exists:teachers,id',
            'subject_schedules.*.classroom_id' => 'required|exists:classrooms,id',
            'subject_schedules.*.days' => 'required|string|max:50',
            'subject_schedules.*.start_time' => 'required|date_format:H:i',
            'subject_schedules.*.end_time' => 'required|date_format:H:i',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                foreach ($this->input('subject_schedules', []) as $index => $slot) {
                    if (! empty($slot['start_time']) && ! empty($slot['end_time']) && $slot['end_time'] <= $slot['start_time']) {
                        $validator->errors()->add("subject_schedules.$index.end_time", 'End time must be after start time.');
                    }
                }

                // Each adviser is committed to one section for the whole school year
                $adviserConflict = DB::table('class_schedules')
                    ->where('academic_year', $this->academic_year)
                    ->where('adviser_id', $this->adviser_id)
                    ->when($this->route('class_schedule'), fn ($query, $classSchedule) => $query->where('id', '!=', $classSchedule->id))
                    ->exists();

                if ($adviserConflict) {
                    $validator->errors()->add('adviser_id', 'This teacher is already advising another section for this school year.');
                }
            },
        ];
    }
}
