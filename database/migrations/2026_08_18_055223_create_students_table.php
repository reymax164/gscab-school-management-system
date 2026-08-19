<?php

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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // foreign key linking back to the User model
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('lrn')->unique()->nullable();
            $table->string('grade_level')->nullable();
            $table->string('enrollment_status')->default('pending'); // pending, enrolled, dropped, graduated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
