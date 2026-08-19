<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'user_id', 
        'lrn',
        'grade_level', 
        'enrollment_status'
    ];

    // links this profile to the authentication account
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}