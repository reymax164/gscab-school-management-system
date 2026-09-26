<?php

namespace App\Models;

use App\Models\Enrollments\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_level',
        'enrollment_status',
    ];

    // links this profile to the authentication account
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // sections
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    // most recent enrolled application, used to surface guardian/contact details
    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class, 'user_id', 'user_id')
            ->where('status', 'enrolled')
            ->latestOfMany();
    }
}
