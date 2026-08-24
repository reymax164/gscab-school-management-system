<?php

namespace App\Models\Enrollments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequirement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The enrollments that have verified this document.
     */
    public function enrollments()
    {
        return $this->belongsToMany(
            Enrollment::class,
            'document_requirement_enrollment'
        )->withTimestamps();
    }
}