<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrescriptionRequestController extends Controller
{
    /**
     * Store a prescription request with image upload.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration_days' => 'nullable|integer|min:1|max:365',
            'instructions' => 'nullable|string|max:1000',
            'prescription_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        // Upload prescription image
        if ($request->hasFile('prescription_image')) {
            $image = $request->file('prescription_image');
            $filename = 'prescription_' . Auth::id() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('prescriptions', $filename, 'public');
            $validated['prescription_image'] = $path;
        }

        // Create health record as prescription request
        $healthRecord = HealthRecord::create([
            'user_id' => Auth::id(),
            'record_type' => 'prescription',
            'title' => 'Prescription Request - ' . $validated['medication_name'],
            'provider_name' => 'Pending Review',
            'medication_name' => $validated['medication_name'],
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'duration_days' => $validated['duration_days'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'prescription_image' => $validated['prescription_image'],
            'approval_status' => 'pending',
            'record_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription request submitted successfully!',
            'prescription' => $healthRecord
        ], 201);
    }

    /**
     * Display prescription requests for user.
     */
    public function index()
    {
        $prescriptions = HealthRecord::where('user_id', Auth::id())
            ->where('record_type', 'prescription')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('prescriptions.index', compact('prescriptions'));
    }
}