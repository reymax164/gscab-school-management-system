<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'hire_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // sections this teacher advises
    public function advisorySections(): HasMany
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }

    // subjects this teacher handles across sections
    public function subjectSchedules(): HasMany
    {
        return $this->hasMany(SubjectSchedule::class);
    }
}
