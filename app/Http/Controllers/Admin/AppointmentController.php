<?php
// ─────────────────────────────────────────────────────────────────────────────
// Replace your app/Http/Controllers/Admin/AppointmentController.php
// with this file. Key changes:
//   - index() map now includes 'specific_service'
//   - update() validation now includes 'specific_service'
// ─────────────────────────────────────────────────────────────────────────────

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

        $query = Appointment::with('user')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');

        if ($request->filled('service'))   $query->where('service_type', $request->service);
        if ($request->filled('status') && $request->status !== 'all') $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('appointment_date', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('appointment_date', '<=', $request->date_to);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name',  'like', "%{$s}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%"));
            });
        }

        $appointments = $query->get()->map(function ($appointment, $index) {
            return [
                'id'               => $appointment->id,
                'no'               => $index + 1,
                'patient_name'     => $appointment->full_name,
                'appointment_date' => $appointment->appointment_date->format('m/d/Y'),
                'appointment_time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A'),
                'service_type'     => $appointment->service_type_label,
                'service_raw'      => $appointment->service_type,
                'specific_service' => $appointment->specific_service ?? '—',   // ← NEW
                'status'           => $appointment->status,
                'status_label'     => $appointment->status_label,
                'is_minor'         => $appointment->is_minor ?? false,
            ];
        })->toArray();

        return view('admin.appointments.index', compact('stats', 'appointments'));
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load('user');
        $prescriptionRequest = null;
        if ($appointment->service_type === 'medicine' && $appointment->user_id) {
            $prescriptionRequest = HealthRecord::where('user_id', $appointment->user_id)
                ->where('record_type', 'prescription')
                ->where('source_appointment_id', $appointment->id)
                ->first();
            if (!$prescriptionRequest) {
                $prescriptionRequest = HealthRecord::where('user_id', $appointment->user_id)
                    ->where('record_type', 'prescription')->latest()->first();
            }
        }
        return view('admin.appointments.show', compact('appointment', 'prescriptionRequest'));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $oldStatus = $appointment->status;
        $appointment->update(['status' => $request->status]);
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $this->createHealthRecordFromAppointment($appointment);
        }
        return redirect()->back()->with('success', 'Appointment status updated successfully!');
    }

    public function edit(Appointment $appointment): View
    {
        // Pass service options so the edit view can show the specific_service dropdown
        $serviceOptions = Appointment::serviceOptions();
        return view('admin.appointments.edit', compact('appointment', 'serviceOptions'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            'specific_service' => 'nullable|string|max:100',   // ← NEW
            'admin_notes'      => 'nullable|string|max:500',
        ]);

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $this->createHealthRecordFromAppointment($appointment);
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Appointment cancelled successfully!');
    }

    private function createHealthRecordFromAppointment(Appointment $appointment): void
    {
        $exists = HealthRecord::where('user_id', $appointment->user_id)
            ->where('source_appointment_id', $appointment->id)->exists();
        if ($exists) return;

        $map        = ['checkup' => 'consultation', 'vaccine' => 'vaccination', 'medicine' => 'prescription'];
        $recordType = $map[$appointment->service_type] ?? 'consultation';

        // Use specific_service as the title if available
        $title = $appointment->specific_service
            ? $appointment->specific_service . ' — ' . $appointment->appointment_date->format('M d, Y')
            : $appointment->service_type_label . ' — ' . $appointment->appointment_date->format('M d, Y');

        $data = [
            'user_id'               => $appointment->user_id,
            'record_type'           => $recordType,
            'title'                 => $title,
            'provider_name'         => 'Tambubong Health Center',
            'record_date'           => $appointment->appointment_date,
            'notes'                 => $appointment->notes ?? null,
            'source_appointment_id' => $appointment->id,
        ];

        if ($recordType === 'prescription') {
            $data['approval_status'] = 'pending';
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('health_records', 'source_appointment_id')) {
            HealthRecord::create($data);
        } else {
            unset($data['source_appointment_id']);
            HealthRecord::create($data);
        }
    }
}