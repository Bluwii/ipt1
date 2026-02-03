<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use App\Models\HealthRecord;
use Illuminate\Database\Seeder;

class HealthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            $this->command->warn('Test user not found. Please run DatabaseSeeder first.');
            return;
        }

        // Create sample consultation records
        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'consultation',
            'title' => 'Annual Physical Examination',
            'provider_name' => 'Dr. Juan Dela Cruz',
            'diagnosis' => 'Overall healthy condition',
            'blood_pressure' => '120/80 mmHg',
            'temperature' => 36.5,
            'heart_rate' => 72,
            'respiratory_rate' => 16,
            'notes' => 'Regular exercise recommended. Follow-up in 6 months.',
            'record_date' => now()->subMonths(4),
        ]);

        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'consultation',
            'title' => 'General Check-up',
            'provider_name' => 'Dr. Maria Santos',
            'diagnosis' => 'Mild cold symptoms',
            'temperature' => 37.2,
            'notes' => 'Prescribed medication for 5 days. Rest and hydration advised.',
            'record_date' => now()->subMonths(7),
        ]);

        // Create sample vaccination records
        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'vaccination',
            'title' => 'COVID-19 Booster Shot',
            'provider_name' => 'Nurse Maria Santos',
            'vaccine_name' => 'Pfizer-BioNTech',
            'lot_number' => 'FF1234',
            'notes' => 'No adverse reactions observed.',
            'record_date' => now()->subMonths(5),
        ]);

        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'vaccination',
            'title' => 'Influenza Vaccine',
            'provider_name' => 'Nurse Ana Cruz',
            'vaccine_name' => 'Quadrivalent Influenza Vaccine',
            'lot_number' => 'FLU5678',
            'next_dose_date' => now()->addMonths(4),
            'notes' => 'Annual vaccination. Next dose in June 2026.',
            'record_date' => now()->subMonths(8),
        ]);

        // Create sample prescription records
        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'prescription',
            'title' => 'Amoxicillin 500mg',
            'provider_name' => 'Dr. Maria Santos',
            'medication_name' => 'Amoxicillin',
            'dosage' => '500mg',
            'frequency' => '3 times daily',
            'duration_days' => 5,
            'instructions' => 'Take after meals with full glass of water',
            'record_date' => now()->subMonths(7),
        ]);

        HealthRecord::create([
            'user_id' => $user->id,
            'record_type' => 'prescription',
            'title' => 'Paracetamol 500mg',
            'provider_name' => 'Dr. Juan Dela Cruz',
            'medication_name' => 'Paracetamol',
            'dosage' => '500mg',
            'frequency' => 'Every 6 hours as needed',
            'duration_days' => null,
            'instructions' => 'Do not exceed 4 tablets in 24 hours. For pain/fever relief.',
            'record_date' => now()->subMonths(9),
        ]);

        $this->command->info('Sample health records created successfully!');
    }
}