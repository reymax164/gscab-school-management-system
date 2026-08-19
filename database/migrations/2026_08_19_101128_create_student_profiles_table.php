<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            
            // Personal Info
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('gender');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('birthplace');
            $table->string('birth_order');
            $table->string('nationality');
            
            // Address Info
            $table->string('house_no')->nullable();
            $table->string('sitio_subdivision')->nullable();
            $table->string('barangay');
            $table->string('province');
            $table->string('zip');
            $table->string('region');
            $table->string('landline')->nullable();
            
            // Family Background (JSON to prevent massive column bloat)
            $table->json('father_details')->nullable();
            $table->json('mother_details')->nullable();
            $table->json('guardian_details')->nullable();
            $table->json('contact_person');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_profiles');
    }
};