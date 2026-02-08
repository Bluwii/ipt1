<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\HealthRecord;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get upcoming appointments
        $upcomingAppointments = Appointment::forUser($user->id)
            ->upcoming()
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        // Get counts for statistics
        $upcomingCount = $upcomingAppointments->count();
        
        $completedCount = Appointment::forUser($user->id)
            ->where('status', 'completed')
            ->count();
        
        $totalRecords = HealthRecord::forUser($user->id)->count();
        
        // Get recent activity (last 3 items)
        $recentActivity = collect([]);
        
        // Get last 2 appointments
        $recentAppointments = Appointment::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        
        foreach ($recentAppointments as $apt) {
            $recentActivity->push([
                'title' => $apt->status === 'completed' 
                    ? 'Appointment completed' 
                    : 'Appointment scheduled',
                'description' => $apt->service_type_label . ' - ' . $apt->appointment_date->format('M d, Y'),
                'time' => $apt->created_at->diffForHumans(),
                'bg_color' => 'bg-blue-100',
                'icon_color' => 'text-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
            ]);
        }
        
        // Get last health record
        $lastRecord = HealthRecord::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastRecord) {
            $recentActivity->push([
                'title' => $lastRecord->record_type_label . ' record added',
                'description' => $lastRecord->title,
                'time' => $lastRecord->created_at->diffForHumans(),
                'bg_color' => 'bg-purple-100',
                'icon_color' => 'text-purple-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            ]);
        }
        
        return view('dashboard', compact(
            'upcomingAppointments',
            'upcomingCount',
            'completedCount',
            'totalRecords',
            'recentActivity'
        ));
    }
}