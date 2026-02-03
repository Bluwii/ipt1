<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
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

        // Create upcoming appointments
        Appointment::create([
            'user_id' => $user->id,
            'service_type' => 'checkup',
            'appointment_date' => now()->addDays(5),
            'appointment_time' => '10:00:00',
            'first_name' => explode(' ', $user->name)[0] ?? 'Test',
            'middle_initial' => '',
            'last_name' => explode(' ', $user->name)[1] ?? 'User',
            'birthdate' => now()->subYears(30),
            'age' => 30,
            'gender' => 'male',
            'email' => $user->email,
            'phone_number' => '09123456789',
            'purok_no' => '1',
            'notes' => 'Regular check-up',
            'status' => 'confirmed',
        ]);

        Appointment::create([
            'user_id' => $user->id,
            'service_type' => 'vaccine',
            'appointment_date' => now()->addDays(10),
            'appointment_time' => '14:00:00',
            'first_name' => explode(' ', $user->name)[0] ?? 'Test',
            'middle_initial' => '',
            'last_name' => explode(' ', $user->name)[1] ?? 'User',
            'birthdate' => now()->subYears(30),
            'age' => 30,
            'gender' => 'male',
            'email' => $user->email,
            'phone_number' => '09123456789',
            'purok_no' => '1',
            'notes' => 'COVID-19 Booster',
            'status' => 'confirmed',
        ]);

        // Create past appointments
        Appointment::create([
            'user_id' => $user->id,
            'service_type' => 'checkup',
            'appointment_date' => now()->subMonths(4),
            'appointment_time' => '09:00:00',
            'first_name' => explode(' ', $user->name)[0] ?? 'Test',
            'middle_initial' => '',
            'last_name' => explode(' ', $user->name)[1] ?? 'User',
            'birthdate' => now()->subYears(30),
            'age' => 30,
            'gender' => 'male',
            'email' => $user->email,
            'phone_number' => '09123456789',
            'purok_no' => '1',
            'notes' => 'Annual physical exam',
            'status' => 'completed',
        ]);

        Appointment::create([
            'user_id' => $user->id,
            'service_type' => 'vaccine',
            'appointment_date' => now()->subMonths(5),
            'appointment_time' => '14:00:00',
            'first_name' => explode(' ', $user->name)[0] ?? 'Test',
            'middle_initial' => '',
            'last_name' => explode(' ', $user->name)[1] ?? 'User',
            'birthdate' => now()->subYears(30),
            'age' => 30,
            'gender' => 'male',
            'email' => $user->email,
            'phone_number' => '09123456789',
            'purok_no' => '1',
            'notes' => 'Flu vaccination',
            'status' => 'completed',
        ]);

        $this->command->info('Sample appointments created successfully!');
    }
}