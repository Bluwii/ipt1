<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(): View
    {
        return view('appointments.index');
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
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'service_type' => 'required|string',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // TODO: Save to database when implemented
        
        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully!');
    }
}