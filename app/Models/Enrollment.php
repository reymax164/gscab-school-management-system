<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'school_year',
        'grade_level',
        'student_status', // 'new', 'existing', 'transferee'
        'status',         // 'submitted', 'registrar_approved', 'cashier_cleared', 'enrolled', 'rejected'
        'online_access',
        'gadgets',        // cast as array
    ];

    protected $casts = [
        'gadgets' => 'array',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function educationalBackground()
    {
        return $this->hasOne(EducationalBackground::class);
    }

    public function payment()
    {
        return $this->hasOne(EnrollmentPayment::class);
    }
}
