<?php
// app/Http/Controllers/Admin/AppointmentController.php
// Replaces / updates the index() method to support filtering by date range and service type.
// All other methods (show, confirm, complete, cancel) remain unchanged.

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class AppointmentController extends Controller
{
    /**
     * index()
     * Supports: search (text), service_type, status, date_from, date_to
     * All filters are optional and stackable.
     */
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

        // ── Filters ────────────────────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name',  'like', "%{$search}%")
                ->orWhere('email',      'like', "%{$search}%");
            });
        }

        if ($service = $request->input('service')) {
            $query->where('service_type', $service);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('appointment_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('appointment_date', '<=', $dateTo);
        }

        $appointments = $query->get()
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
                    'is_minor'         => $appointment->is_minor ?? false,
                ];
            })->toArray();

        return view('admin.appointments.index', compact('stats', 'appointments'));
    }

    /** Show a single appointment */
    public function show(Appointment $appointment)
    {
        $appointment->load('user');
        return view('admin.appointments.show', compact('appointment'));
    }

    /** Confirm a pending appointment */
    public function confirm(Appointment $appointment)
    {
        try {
            abort_unless($appointment->status === 'pending', 422, 'Only pending appointments can be confirmed.');
            $appointment->update(['status' => 'confirmed']);
            return back()->with('success', 'Appointment confirmed.');
        } catch (\Exception $e) {
            Log::error('AppointmentController@confirm: ' . $e->getMessage());
            return back()->with('error', 'Could not confirm appointment.');
        }
    }

    /** Mark appointment as completed */
    public function complete(Appointment $appointment)
    {
        try {
            abort_unless($appointment->status === 'confirmed', 422, 'Only confirmed appointments can be completed.');
            $appointment->update(['status' => 'completed']);
            return back()->with('success', 'Appointment marked as completed.');
        } catch (\Exception $e) {
            Log::error('AppointmentController@complete: ' . $e->getMessage());
            return back()->with('error', 'Could not complete appointment.');
        }
    }

    /** Cancel an appointment */
    public function cancel(Appointment $appointment)
    {
        try {
            abort_unless(in_array($appointment->status, ['pending','confirmed']), 422, 'Cannot cancel this appointment.');
            $appointment->update(['status' => 'cancelled']);
            return back()->with('success', 'Appointment cancelled.');
        } catch (\Exception $e) {
            Log::error('AppointmentController@cancel: ' . $e->getMessage());
            return back()->with('error', 'Could not cancel appointment.');
        }
    }
}