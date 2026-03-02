<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
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
                    'status'           => $appointment->status,
                    'status_label'     => $appointment->status_label,
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

        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        $appointment->update(['status' => $newStatus]);

        // Auto-create health record when appointment is COMPLETED
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $this->createHealthRecordFromAppointment($appointment);
        }

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

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        // Auto-create health record when marked completed
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $this->createHealthRecordFromAppointment($appointment);
        }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Appointment cancelled successfully!');
    }

    /**
     * When an appointment is completed, automatically create the matching health record
     * so it appears in the patient's records tab on both admin and user side.
     *
     * checkup   → consultation record
     * vaccine   → vaccination record
     * medicine  → prescription record
     */
    private function createHealthRecordFromAppointment(Appointment $appointment): void
    {
        // Don't duplicate if a record for this appointment already exists
        $exists = HealthRecord::where('user_id', $appointment->user_id)
            ->where('source_appointment_id', $appointment->id)
            ->exists();

        if ($exists) {
            return;
        }

        $map = [
            'checkup'  => 'consultation',
            'vaccine'  => 'vaccination',
            'medicine' => 'prescription',
        ];

        $recordType = $map[$appointment->service_type] ?? 'consultation';

        $data = [
            'user_id'               => $appointment->user_id,
            'record_type'           => $recordType,
            'title'                 => $appointment->service_type_label . ' — ' .
                                       $appointment->appointment_date->format('M d, Y'),
            'provider_name'        => 'Tambubong Health Center',
            'record_date'           => $appointment->appointment_date,
            'notes'                 => $appointment->notes ?? null,
            'source_appointment_id' => $appointment->id,
        ];

        // For prescriptions default approval to 'pending' so admin can review
        if ($recordType === 'prescription') {
            $data['approval_status'] = 'pending';
        }

        // Only set if the column exists (safe guard)
        if (\Illuminate\Support\Facades\Schema::hasColumn('health_records', 'source_appointment_id')) {
            HealthRecord::create($data);
        } else {
            unset($data['source_appointment_id']);
            HealthRecord::create($data);
        }
    }
}