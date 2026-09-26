<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level',
        'classroom_id',
        'adviser_id',
        'school_year',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'adviser_id');
    }

    public function subjectSchedules(): HasMany
    {
        return $this->hasMany(SubjectSchedule::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    // adviser or subject-teacher membership check, used to authorize teacher-portal access
    public function hasTeacher(Teacher $teacher): bool
    {
        return $this->adviser_id === $teacher->id
            || $this->subjectSchedules()->where('teacher_id', $teacher->id)->exists();
    }

    public function scopeForTeacher(Builder $query, Teacher $teacher): Builder
    {
        return $query->where('adviser_id', $teacher->id)
            ->orWhereIn('id', $teacher->subjectSchedules()->pluck('section_id'));
    }
}
