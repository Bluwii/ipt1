<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\HealthRecord;
use ulluminate\Support\facades\DB;
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
        ];
        
        // Prepare chart data (last 6 months)
        $chartData = [
            'labels' => [],
            'data' => [],
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData['labels'][] = $date->format('M Y');
            $chartData['data'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->count();
        }
        
        // Get recent appointments with user relationship
        $recentAppointments = Appointment::with('user')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'patient_name' => $appointment->full_name,
                    'service' => $appointment->service_type_label,
                    'date' => $appointment->appointment_date->format('M d, Y'),
                    'time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A'),
                    'status' => $appointment->status_label,
                    'status_class' => $appointment->status === 'completed' ? 'success' : 
                        ($appointment->status === 'pending' ? 'warning' : 
                        ($appointment->status === 'confirmed' ? 'info' : 'danger')),
                ];
            });
        
        // Get recent notifications (using latest appointments)
        $notifications = Appointment::with('user')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'message' => 'New appointment request from ' . $appointment->user->name,
                    'time' => $appointment->created_at->diffForHumans(),
                    'type' => 'appointment',
                ];
            });
        
        return view('admin.dashboard', compact('stats', 'chartData', 'recentAppointments', 'notifications'));
    }

}