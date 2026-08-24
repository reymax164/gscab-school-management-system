<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('payment_scheme');
            $table->string('payment_method')->nullable();
            
            // finance Tracking
            $table->decimal('tuition_fee', 10, 2)->nullable();
            $table->decimal('misc_fee', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            
            $table->string('payment_status')->default('pending'); // pending, paid, verified
            $table->string('or_number')->nullable(); // official receipt
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); // tracks which cashier verified it
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollment_payments');
    }
};