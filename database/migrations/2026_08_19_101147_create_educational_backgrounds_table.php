<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('educational_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('last_school')->nullable();
            $table->string('school_address')->nullable();
            $table->string('school_year')->nullable();
            $table->string('school_type')->nullable(); // public or private
            $table->text('honors_awards')->nullable();
            $table->string('gen_ave')->nullable();
            $table->text('talent_skills')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('educational_backgrounds');
    }
};