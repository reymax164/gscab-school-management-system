<?php

namespace App\Models\Enrollments;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'enrollment_id', 'lrn', 'email', 'religion',
        'last_name', 'first_name', 'middle_name', 'gender', 
        'birthdate', 'age', 'birthplace', 'birth_order', 'nationality',
        'house_no', 'sitio_subdivision', 'barangay', 'province', 'zip', 'region', 'landline',
        'father_details', 'mother_details', 'guardian_details', 'contact_person'
    ];

    protected $casts = [
        'father_details' => 'array',
        'mother_details' => 'array',
        'guardian_details' => 'array',
        'contact_person' => 'array',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
