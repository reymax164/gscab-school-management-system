<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('school_year');
            $table->string('grade_level');
            $table->enum('student_status', ['new', 'existing', 'transferee']);
            $table->string('status')->default('submitted'); // registrar_approved, cashier_cleared, enrolled
            $table->string('online_access');
            $table->json('gadgets')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};