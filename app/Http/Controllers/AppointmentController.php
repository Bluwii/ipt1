<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    // Allowed hours and max slots per hour
    const ALLOWED_HOURS = [8, 9, 10, 11, 12];
    const MAX_SLOTS_PER_HOUR = 10;

    /**
     * Display a listing of appointments.
     */
    public function index(): View
    {
        $user = Auth::user();

        $upcomingAppointments = Appointment::forUser($user->id)
            ->upcoming()
            ->get();

        $pastAppointments = Appointment::forUser($user->id)
            ->past()
            ->get();

        $stats = [
            'total'     => Appointment::forUser($user->id)->count(),
            'upcoming'  => $upcomingAppointments->count(),
            'completed' => Appointment::forUser($user->id)->where('status', 'completed')->count(),
        ];

        return view('appointments.index', compact('upcomingAppointments', 'pastAppointments', 'stats'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(): View
    {
        return view('appointments.create');
    }

    /**
     * Return slot availability for a given date.
     * Returns an array of hours (8–12) with booked count and whether they are full.
     */
    public function slotAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after:today',
        ]);

        $date = $request->input('date');

        $slots = [];
        foreach (self::ALLOWED_HOURS as $hour) {
            $timeString = sprintf('%02d:00:00', $hour);

            $booked = Appointment::whereDate('appointment_date', $date)
                ->where('appointment_time', $timeString)
                ->whereNotIn('status', ['cancelled'])
                ->count();

            $slots[] = [
                'hour'      => $hour,
                'time_value' => sprintf('%02d:00', $hour),
                'label'     => $hour <= 11
                    ? $hour . ':00 AM'
                    : '12:00 PM',
                'booked'    => $booked,
                'available' => max(0, self::MAX_SLOTS_PER_HOUR - $booked),
                'is_full'   => $booked >= self::MAX_SLOTS_PER_HOUR,
            ];
        }

        return response()->json(['slots' => $slots]);
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // Determine minor status early so we can apply conditional validation rules
        $submittedAge = (int) $request->input('age', 999);
        $isMinor      = $submittedAge < 18;

        $validated = $request->validate([
            'service_type'     => 'required|in:checkup,vaccine,medicine',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|in:08:00,09:00,10:00,11:00,12:00',
            'first_name'       => 'required|string|max:255',
            'middle_initial'   => 'nullable|string|max:1',
            'last_name'        => 'required|string|max:255',
            'birthdate'        => 'required|date|before:today',
            'age'              => 'required|integer|min:0|max:120',
            'gender'           => 'required|in:male,female,other',
            'email'            => 'required|email|max:255',
            'phone_number'     => 'required|string|max:20',
            'purok_no'         => 'required|string',
            'notes'            => 'nullable|string|max:500',

            // Guardian fields — only required when patient is under 18
            'guardian_name'         => $isMinor ? 'required|string|max:255'            : 'nullable|string|max:255',
            'guardian_relationship' => $isMinor ? 'required|in:mother,father,guardian' : 'nullable|in:mother,father,guardian',
            'guardian_contact'      => $isMinor ? 'required|string|max:20'             : 'nullable|string|max:20',
            'guardian_consent'      => $isMinor ? 'required|accepted'                  : 'nullable|boolean',
        ]);

        // Check slot availability before booking
        $hour        = (int) explode(':', $validated['appointment_time'])[0];
        $timeString  = sprintf('%02d:00:00', $hour);

        $booked = Appointment::whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $timeString)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($booked >= self::MAX_SLOTS_PER_HOUR) {
            $label = $hour <= 11 ? $hour . ':00 AM' : '12:00 PM';
            $message = "The {$label} slot is already full (10/10 appointments). Please choose another time.";

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->withErrors(['appointment_time' => $message]);
        }

        $validated['user_id']  = Auth::id();
        $validated['status']   = 'pending';
        $validated['is_minor'] = $isMinor;

        // If adult, clear any accidentally-submitted guardian fields
        if (! $isMinor) {
            $validated['guardian_name']         = null;
            $validated['guardian_relationship'] = null;
            $validated['guardian_contact']      = null;
            $validated['guardian_consent']      = false;
        }

        // Normalize time to HH:00:00 format stored in DB
        $validated['appointment_time'] = $timeString;

        $appointment = Appointment::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success'     => true,
                'message'     => 'Appointment booked successfully!',
                'appointment' => $appointment,
            ], 201);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment): View
    {
        $this->authorize('update', $appointment);
        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'service_type'     => 'required|in:checkup,vaccine,medicine',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:08:00,09:00,10:00,11:00,12:00',
            'notes'            => 'nullable|string|max:500',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->update(['status' => 'cancelled']);
            return redirect()->route('appointments.index')
                ->with('success', 'Appointment cancelled successfully!');
        }

        return redirect()->route('appointments.index')
            ->with('error', 'Cannot cancel this appointment.');
    }
}