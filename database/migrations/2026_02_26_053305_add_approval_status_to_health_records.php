<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            // Prescription approval workflow
            if (!Schema::hasColumn('health_records', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('record_type');
            }
            if (!Schema::hasColumn('health_records', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('approval_status');
            }
            // Prescription-specific fields
            if (!Schema::hasColumn('health_records', 'medication_name')) {
                $table->string('medication_name')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('health_records', 'dosage')) {
                $table->string('dosage')->nullable()->after('medication_name');
            }
            if (!Schema::hasColumn('health_records', 'frequency')) {
                $table->string('frequency')->nullable()->after('dosage');
            }
            if (!Schema::hasColumn('health_records', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('frequency');
            }
            if (!Schema::hasColumn('health_records', 'instructions')) {
                $table->text('instructions')->nullable()->after('duration_days');
            }
            if (!Schema::hasColumn('health_records', 'prescription_image')) {
                $table->string('prescription_image')->nullable()->after('instructions');
            }
            // Vaccination-specific fields
            if (!Schema::hasColumn('health_records', 'vaccine_name')) {
                $table->string('vaccine_name')->nullable()->after('prescription_image');
            }
            if (!Schema::hasColumn('health_records', 'lot_number')) {
                $table->string('lot_number')->nullable()->after('vaccine_name');
            }
            if (!Schema::hasColumn('health_records', 'next_dose_date')) {
                $table->date('next_dose_date')->nullable()->after('lot_number');
            }
            // Consultation-specific fields
            if (!Schema::hasColumn('health_records', 'diagnosis')) {
                $table->string('diagnosis')->nullable()->after('next_dose_date');
            }
            if (!Schema::hasColumn('health_records', 'blood_pressure')) {
                $table->string('blood_pressure')->nullable()->after('diagnosis');
            }
            if (!Schema::hasColumn('health_records', 'temperature')) {
                $table->decimal('temperature', 4, 1)->nullable()->after('blood_pressure');
            }
            if (!Schema::hasColumn('health_records', 'heart_rate')) {
                $table->integer('heart_rate')->nullable()->after('temperature');
            }
            if (!Schema::hasColumn('health_records', 'respiratory_rate')) {
                $table->integer('respiratory_rate')->nullable()->after('heart_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $columns = [
                'approval_status', 'admin_notes', 'medication_name', 'dosage',
                'frequency', 'duration_days', 'instructions', 'prescription_image',
                'vaccine_name', 'lot_number', 'next_dose_date',
                'diagnosis', 'blood_pressure', 'temperature', 'heart_rate', 'respiratory_rate',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('health_records', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};