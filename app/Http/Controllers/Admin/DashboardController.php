<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(): View
    {
        // Get real statistics from database
        $stats = [
            'total_patients' => User::where('role', 'user')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_prescriptions' => HealthRecord::where('record_type', 'prescription')->count(),
            'completed_today' => Appointment::whereDate('appointment_date', today())
                ->where('status', 'completed')
                ->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
        ];
        
        // Prepare chart data (last 6 months)
        $chartData = [
            'labels' => [],
            'checkups' => [],
            'vaccines' => [],
            'medicine' => [],
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData['labels'][] = $date->format('M Y');
            
            $chartData['checkups'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'checkup')
                ->count();
                
            $chartData['vaccines'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'vaccine')
                ->count();
                
            $chartData['medicine'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'medicine')
                ->count();
        }
        
        // Get recent appointments with user relationship
        $recentAppointments = Appointment::with('user')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->take(5)
            ->get()
            ->map(function ($appointment, $index) {
                return [
                    'id' => $index + 1,
                    'patient_name' => $appointment->full_name,
                    'service' => $appointment->service_type_label,
                    'appointment_date' => $appointment->appointment_date->format('M. d, Y'),
                    'status' => $appointment->status_label,
                    'user_id' => str_pad($appointment->user_id, 3, '0', STR_PAD_LEFT),
                ];
            });
        
        $notifications = Appointment::with('user')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'user' => $appointment->user->name ?? 'Unknown User',
                    'message' => 'New ' . $appointment->service_type_label . ' appointment',
                    'time' => $appointment->created_at->diffForHumans(),
                    'type' => 'appointment',
                ];
            });
        
        return view('admin.dashboard', compact('stats', 'chartData', 'recentAppointments', 'notifications'));
    }
}