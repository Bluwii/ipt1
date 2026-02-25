<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total'    => Appointment::count(),
            'pending'  => Appointment::where('status', 'pending')->count(),
            'complete' => Appointment::where('status', 'completed')->count(),
            'cancel'   => Appointment::where('status', 'cancelled')->count(),
        ];

        $appointments = Appointment::with('user')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get()
            ->map(function ($appointment, $index) {
                return [
                    'id'               => $appointment->id,
                    'no'               => $index + 1,
                    'patient_name'     => $appointment->full_name,
                    'appointment_date' => $appointment->appointment_date->format('m/d/Y'),
                    'appointment_time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A'),
                    'service_type'     => $appointment->service_type_label,
                    'status'           => $appointment->status,          // raw status for logic
                    'status_label'     => $appointment->status_label,    // label for display
                ];
            })->toArray();

        return view('admin.appointments.index', compact('stats', 'appointments'));
    }

    public function show(Appointment $appointment): View
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Appointment status updated successfully!');
    }

    public function edit(Appointment $appointment): View
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Appointment cancelled successfully!');
    }
}