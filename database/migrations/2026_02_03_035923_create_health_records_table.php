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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            
            // Record Type
            $table->enum('record_type', ['consultation', 'vaccination', 'prescription']);
            
            // Consultation Details
            $table->string('title');
            $table->string('provider_name'); // Doctor/Nurse name
            $table->text('diagnosis')->nullable();
            $table->text('notes')->nullable();
            
            // Vital Signs (for consultations)
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            
            // Vaccination Details
            $table->string('vaccine_name')->nullable();
            $table->string('lot_number')->nullable();
            $table->date('next_dose_date')->nullable();
            
            // Prescription Details
            $table->string('medication_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('duration_days')->nullable();
            $table->text('instructions')->nullable();
            
            // Record Date
            $table->date('record_date');
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('record_type');
            $table->index('record_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};