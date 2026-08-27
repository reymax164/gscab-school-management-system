<?php

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('grade_level'); // e.g., '7', '8', 'Kinder'
            $table->foreignIdFor(Classroom::class)->constrained()->cascadeOnDelete(); // room used for the whole day
            $table->foreignIdFor(Teacher::class, 'adviser_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('academic_year'); // e.g., '2026-2027'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
