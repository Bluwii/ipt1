<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrescriptionRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration_days' => 'nullable|integer|min:1',
            'instructions' => 'nullable|string|max:500',
            'prescription_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        // Handle image upload
        if ($request->hasFile('prescription_image')) {
            $image = $request->file('prescription_image');
            $filename = 'prescription_' . Auth::id() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('prescriptions', $filename, 'public');
            $validated['prescription_image'] = $path;
        }

        // Create prescription request
        $validated['user_id'] = Auth::id();
        $validated['record_type'] = 'prescription';
        $validated['title'] = $validated['medication_name'];
        $validated['provider_name'] = 'Pending Approval';
        $validated['record_date'] = now();
        $validated['approval_status'] = 'pending';

        HealthRecord::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Prescription request submitted successfully! Please wait 1-2 days for admin approval.'
        ]);
    }
}