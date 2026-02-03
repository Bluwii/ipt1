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
    /**
     * Display a listing of appointments.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get upcoming and past appointments
        $upcomingAppointments = Appointment::forUser($user->id)
            ->upcoming()
            ->get();
            
        $pastAppointments = Appointment::forUser($user->id)
            ->past()
            ->get();
        
        // Get statistics
        $stats = [
            'total' => Appointment::forUser($user->id)->count(),
            'upcoming' => $upcomingAppointments->count(),
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
     * Store a newly created appointment.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'service_type' => 'required|in:checkup,vaccine,medicine',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'purok_no' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Add user_id and default status
        $validated['user_id'] = Auth::user()->id;
        $validated['status'] = 'pending';

        // Create the appointment
        $appointment = Appointment::create($validated);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'appointment' => $appointment,
            ], 201);
        }

        // Return redirect for regular form submissions
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): View
    {
        // Ensure the appointment belongs to the authenticated user
        $this->authorize('view', $appointment);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment): View
    {
        // Ensure the appointment belongs to the authenticated user
        $this->authorize('update', $appointment);
        
        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        // Ensure the appointment belongs to the authenticated user
        $this->authorize('update', $appointment);
        
        // Validate the request
        $validated = $request->validate([
            'service_type' => 'required|in:checkup,vaccine,medicine',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        // Update the appointment
        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        // Ensure the appointment belongs to the authenticated user
        $this->authorize('delete', $appointment);
        
        // Only allow cancellation of pending or confirmed appointments
        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->update(['status' => 'cancelled']);
            return redirect()->route('appointments.index')
                ->with('success', 'Appointment cancelled successfully!');
        }

        return redirect()->route('appointments.index')
            ->with('error', 'Cannot cancel this appointment.');
    }
}