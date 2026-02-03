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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Service Information
            $table->enum('service_type', ['checkup', 'vaccine', 'medicine']);
            
            // Appointment Schedule
            $table->date('appointment_date');
            $table->time('appointment_time');
            
            // Patient Details
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->integer('age');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('email');
            $table->string('phone_number');
            $table->string('purok_no');
            
            // Additional Information
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            
            // Admin notes
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};