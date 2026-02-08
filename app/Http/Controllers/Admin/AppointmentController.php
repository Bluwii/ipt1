<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
        public function index(Request $request): View
    {
        // Get real statistics from database
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'complete' => Appointment::where('status', 'completed')->count(),
            'cancel' => Appointment::where('status', 'cancelled')->count(),
        ];
        
        // Get all appointments from database with user relationship
        $appointments = Appointment::with('user')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get()
            ->map(function ($appointment, $index) {
                return [
                    'id' => $appointment->id,
                    'no' => $index + 1,
                    'patient_name' => $appointment->full_name,
                    'appointment_date' => $appointment->appointment_date->format('m/d/Y'),
                    'appointment_time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A'),
                    'service_type' => $appointment->service_type_label,
                    'status' => $appointment->status_label,
                ];
            })->toArray();
        
        return view('admin.appointments.index', compact('stats', 'appointments'));
    }
}