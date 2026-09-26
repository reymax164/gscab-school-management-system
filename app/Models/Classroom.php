<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'building_name'];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
