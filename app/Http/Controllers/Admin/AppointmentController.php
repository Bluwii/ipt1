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
        // Static statistics for appointment page
        $stats = [
            'total' => 50,
            'pending' => 10,
            'complete' => 35,
            'cancel' => 5,
        ];
        
        // Static appointments data
        $appointments = [
            [
                'id' => 1,
                'no' => 1,
                'patient_name' => 'Trisha Mendoza',
                'appointment_date' => '10/04/2025',
                'appointment_time' => '8:00 AM',
                'service_type' => 'Medicine Request',
                'status' => 'Pending',
            ],
            [
                'id' => 2,
                'no' => 2,
                'patient_name' => 'Mark Gonzales',
                'appointment_date' => '10/04/2025',
                'appointment_time' => '9:00 AM',
                'service_type' => 'Vaccine',
                'status' => 'Complete',
            ],
            [
                'id' => 3,
                'no' => 3,
                'patient_name' => 'Irene Diño',
                'appointment_date' => '10/04/2025',
                'appointment_time' => '9:00 AM',
                'service_type' => 'Check Up',
                'status' => 'Complete',
            ],
            [
                'id' => 4,
                'no' => 4,
                'patient_name' => 'Annabelle Gutierrez',
                'appointment_date' => '6/04/2025',
                'appointment_time' => '10:00 AM',
                'service_type' => 'Check Up',
                'status' => 'Complete',
            ],
            [
                'id' => 5,
                'no' => 5,
                'patient_name' => 'Mark Christian Santos',
                'appointment_date' => '6/04/2025',
                'appointment_time' => '11:00 AM',
                'service_type' => 'Vaccine',
                'status' => 'Complete',
            ],
            [
                'id' => 6,
                'no' => 6,
                'patient_name' => 'Angelo Mae Ramos',
                'appointment_date' => '6/04/2025',
                'appointment_time' => '9:00 AM',
                'service_type' => 'Medicine Request',
                'status' => 'Pending',
            ],
        ];
        
        return view('admin.appointments.index', compact('stats', 'appointments'));
    }
    
    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): View
    {
        return view('admin.appointments.show', compact('appointment'));
    }
    
    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment): View
    {
        return view('admin.appointments.edit', compact('appointment'));
    }
    
    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        // Validation and update logic here
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }
    
    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);
        
        $appointment->update(['status' => $validated['status']]);
        
        return redirect()->back()
            ->with('success', 'Appointment status updated successfully!');
    }
    
    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }
}