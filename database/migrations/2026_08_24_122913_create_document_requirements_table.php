<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('document_requirement_enrollment', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('enrollment_id')
                  ->constrained()
                  ->cascadeOnDelete();
                  
            $table->foreignId('document_requirement_id')
                  ->constrained('document_requirements')
                  ->cascadeOnDelete();
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requirement_enrollment');
        Schema::dropIfExists('document_requirements');
    }
};