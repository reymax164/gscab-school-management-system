<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'lrn',
        'grade_level',
        'enrollment_status',
    ];

    // links this profile to the authentication account
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // schedules
    public function classSchedules(): BelongsToMany
    {
        return $this->belongsToMany(ClassSchedule::class)
            ->withPivot('status')
            ->withTimestamps();
    }
}
