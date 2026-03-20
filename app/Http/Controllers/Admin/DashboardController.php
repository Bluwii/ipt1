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
    public function index(): View
    {
        $stats = [
            'total_patients'         => User::where('role', 'user')->count(),
            'today_appointments'     => Appointment::whereDate('appointment_date', today())->count(),
            'pending_prescriptions'  => \Illuminate\Support\Facades\Schema::hasColumn('health_records', 'approval_status')
                                            ? HealthRecord::where('record_type', 'prescription')
                                                ->where('approval_status', 'pending')->count()
                                            : HealthRecord::where('record_type', 'prescription')->count(),
            'completed_today'        => Appointment::whereDate('appointment_date', today())
                                            ->where('status', 'completed')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
        ];

        // Chart data — last 6 months
        $chartData = ['labels' => [], 'checkups' => [], 'vaccines' => [], 'medicine' => []];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData['labels'][]   = $date->format('M Y');
            $chartData['checkups'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'checkup')->count();
            $chartData['vaccines'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'vaccine')->count();
            $chartData['medicine'][] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->where('service_type', 'medicine')->count();
        }

        // Recent appointments (last 5)
        $recentAppointments = Appointment::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($appointment, $index) {
                return [
                    'no'               => $index + 1,
                    'id'               => $appointment->id,
                    'patient_name'     => $appointment->full_name,
                    'service'          => $appointment->service_type_label,
                    'appointment_date' => $appointment->appointment_date->format('M. d, Y'),
                    'status'           => $appointment->status,       // raw for badge logic
                    'status_label'     => $appointment->status_label, // label for display
                    'user_id'          => str_pad($appointment->user_id, 3, '0', STR_PAD_LEFT),
                    'specific_service' => $appointment->specific_service ?? '—',
                ];
            });

        // Notifications: recent appointments + pending prescriptions
        $appointmentNotifs = Appointment::with('user')
            ->where('created_at', '>=', now()->subDays(14))
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->map(function ($appointment) {
                return [
                    'user'    => $appointment->user->name ?? 'Unknown User',
                    'message' => 'Booked a ' . $appointment->service_type_label . ' appointment',
                    'time'    => $appointment->created_at->diffForHumans(),
                    'type'    => 'appointment',
                    'created_at' => $appointment->created_at,
                ];
            });

        $prescriptionNotifs = \Illuminate\Support\Facades\Schema::hasColumn('health_records', 'approval_status')
            ? HealthRecord::with('user')
                ->where('record_type', 'prescription')
                ->where('approval_status', 'pending')
                ->where('created_at', '>=', now()->subDays(14))
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get()
                ->map(function ($record) {
                    return [
                        'user'    => $record->user->name ?? 'Unknown User',
                        'message' => 'Submitted a prescription request',
                        'time'    => $record->created_at->diffForHumans(),
                        'type'    => 'prescription',
                        'created_at' => $record->created_at,
                    ];
                })
            : collect();

        $notifications = $appointmentNotifs->concat($prescriptionNotifs)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentAppointments', 'notifications'));
    }
}