<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(): View
    {
        // Get today's statistics
        $todayAppointments = 86; // Static for now
        $pendingAppointments = 18; // Static
        $completedAppointments = 120; // Static
        $pendingPrescriptions = 38; // Static
        
        // Get statistics for cards
        $stats = [
            'total_patients' => 1250,
            'today_appointments' => $todayAppointments,
            'completed_appointments' => $completedAppointments,
            'pending_prescriptions' => $pendingPrescriptions,
        ];
        
        // Chart data for appointments (static)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'checkups' => [45, 52, 48, 65, 70, 58],
            'vaccines' => [30, 35, 42, 38, 45, 40],
            'medicine' => [25, 28, 30, 32, 35, 28],
        ];
        
        // Recent appointments (static)
        $recentAppointments = [
            [
                'id' => 1,
                'patient_name' => 'Faith Mariel Mendoza',
                'appointment_date' => '04/04/2025',
                'appointment_time' => '8:00 AM',
                'status' => 'Done',
                'user_id' => 'User01',
                'service' => 'Check Up'
            ],
            [
                'id' => 2,
                'patient_name' => 'Jilbert Mae Padolina',
                'appointment_date' => '04/04/2025',
                'appointment_time' => '9:00 AM',
                'status' => 'Done',
                'user_id' => 'User02',
                'service' => 'Vaccine'
            ],
            [
                'id' => 3,
                'patient_name' => 'Tristan Tankoko',
                'appointment_date' => '05/04/2025',
                'appointment_time' => '9:00 AM',
                'status' => 'Done',
                'user_id' => 'User03',
                'service' => 'Vaccination'
            ],
            [
                'id' => 4,
                'patient_name' => 'Nora Elizabeth Padiaga',
                'appointment_date' => '05/04/2025',
                'appointment_time' => '10:00 AM',
                'status' => 'User05',
                'user_id' => 'User04',
                'service' => 'Check Up'
            ],
            [
                'id' => 5,
                'patient_name' => 'Marvic Valencia',
                'appointment_date' => '06/04/2025',
                'appointment_time' => '11:00 AM',
                'status' => 'Done',
                'user_id' => 'User07',
                'service' => 'Request Medicine'
            ],
        ];
        
        // Notification/Updates (Frame 392)
        $notifications = [
            [
                'user' => 'User109',
                'message' => 'Requested an appointment',
                'time' => 'recent'
            ],
            [
                'user' => 'User12',
                'message' => 'Had successfully appointment',
                'time' => 'recent'
            ],
            [
                'user' => 'User34',
                'message' => 'Had successfully appointment',
                'time' => 'recent'
            ],
            [
                'user' => 'User16',
                'message' => 'Had successfully appointment',
                'time' => 'recent'
            ],
        ];
        
        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'recentAppointments',
            'notifications'
        ));
    }
}