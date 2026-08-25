<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- enrollment data ---
            'grade_level' => ['required', 'string'],
            'student_status' => ['required', 'string', Rule::in(['new', 'existing', 'transferee'])],
            'online_access' => ['required', 'string', Rule::in(['no', 'wifi', 'postpaid', 'prepaid'])], // FIX: Changed to string ENUMs
            'gadgets' => ['nullable', 'array'],
            'gadgets.*' => ['string'], 
            'email' => ['required', 'email'],
            'lrn' => ['required', 'numeric'],

            // --- personal info ---
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
            'age' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'string', Rule::in(['male', 'female'])],
            'birthplace' => ['required', 'string', 'max:255'],
            'birth_order' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'house_no' => ['required', 'string', 'max:255'],
            'sitio_subdivision' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'religion' => ['required', 'string', 'max:255'],
            'landline' => ['nullable', 'string', 'max:255'],

            // --- father's info ---
            'father_deceased' => ['required', 'string', Rule::in(['yes', 'no'])],
            'father_last_name' => ['required', 'string', 'max:255'],
            'father_first_name' => ['required', 'string', 'max:255'],
            'father_middle_name' => ['required', 'string', 'max:255'],
            'father_age' => ['required', 'numeric', 'min:0'],
            'father_address' => ['required', 'string'],
            'father_number' => ['required_if:father_deceased,no', 'nullable', 'numeric'],
            'father_occupation' => ['required_if:father_deceased,no', 'nullable', 'string'],

            // --- mother's info ---
            'mother_deceased' => ['required', 'string', Rule::in(['yes', 'no'])],
            'mother_maiden_last' => ['required', 'string', 'max:255'],
            'mother_first_name' => ['required', 'string', 'max:255'],
            'mother_maiden_middle' => ['required', 'string', 'max:255'],
            'mother_age' => ['required', 'numeric', 'min:0'],
            'mother_address' => ['required', 'string'],
            'mother_number' => ['required_if:mother_deceased,no', 'nullable', 'numeric'],
            'mother_occupation' => ['required_if:mother_deceased,no', 'nullable', 'string'],

            // --- guardian's info ---
            'guardian_name' => ['nullable', 'string', 'max:255'], // Made nullable (form says "If any")
            'guardian_relation' => ['nullable', 'string', 'max:255'],
            'guardian_address' => ['nullable', 'string'],
            'guardian_number' => ['nullable', 'numeric'],
            'guardian_occupation' => ['nullable', 'string'],

            // --- contact person ---
            'con_person_name' => ['required', 'string', 'max:255'],
            'con_person_relation' => ['required', 'string', 'max:255'],
            'con_person_number' => ['required', 'numeric'],
            'con_person_address' => ['required', 'string'],

            // --- educational background ---
            'last_school' => [
                Rule::requiredIf(fn () => in_array($this->student_status, ['new', 'transferee'])),
                'nullable', 'string'
            ],
            'last_school_address' => ['nullable', 'string'], 
            'last_school_year' => ['nullable', 'string'], 
            'last_school_type' => ['nullable', 'string', Rule::in(['public', 'private'])], 
            'gen_ave' => [
                Rule::requiredIf(fn () => $this->student_status === 'transferee'),
                'nullable', 'numeric', 'between:75,100'
            ],
            'talent_skills' => ['nullable', 'string'], 

            // --- payment scheme ---
            'payment_scheme' => ['required', 'string', Rule::in(['full', 'option1', 'option2', 'special'])],
        ];
    }

    public function messages(): array
    {
        return [
            'father_number.required_if' => 'The father\'s contact number is required.',
            'father_occupation.required_if' => 'The father\'s occupation is required.',
            'mother_number.required_if' => 'The mother\'s contact number is required.',
            'mother_occupation.required_if' => 'The mother\'s occupation is required.',
            'last_school.required' => 'Previous school details are required for new students and transferees.',
            'gen_ave.required' => 'Transferees must provide their general average.',
        ];
    }
}