<?php

namespace App\Models\Enrollments;

use App\Models\User;
use App\Models\Enrollments\DocumentRequirement;
use Illuminate\Database\Eloquent\Model;


class Enrollment extends Model
{
    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'reference_code',
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

    public function submittedDocuments()
    {
        return $this->belongsToMany(
            DocumentRequirement::class,
            'document_requirement_enrollment', // explicit pivot table name
            'enrollment_id',                  // foreign key on pivot table for Enrollment
            'document_requirement_id'         // foreign key on pivot table for DocumentRequirement
        )->withTimestamps();
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
