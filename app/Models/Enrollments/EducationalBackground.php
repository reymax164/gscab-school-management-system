<?php

namespace App\Models\Enrollments;

use Illuminate\Database\Eloquent\Model;

class EducationalBackground extends Model
{
    protected $fillable = [
        'enrollment_id', 'last_school', 'school_address', 'school_year',
        'school_type', 'honors_awards', 'gen_ave', 'talent_skills'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
