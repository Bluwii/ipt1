<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PrescriptionRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_name'    => 'required|string|max:255',
            'dosage'             => 'required|string|max:100',
            'quantity_requested' => 'nullable|integer|min:1',
            'frequency'          => 'nullable|string|max:100',
            'duration_days'      => 'nullable|integer|min:0',
            'instructions'       => 'nullable|string|max:500',
            'prescription_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle optional image upload
        if ($request->hasFile('prescription_image')) {
            $image    = $request->file('prescription_image');
            $filename = 'prescription_' . Auth::id() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path     = $image->storeAs('prescriptions', $filename, 'public');
            $validated['prescription_image'] = $path;
        }

        // Build the base payload — always safe columns
        $data = [
            'user_id'         => Auth::id(),
            'record_type'     => 'prescription',
            'title'           => $validated['medication_name'],
            'provider_name'   => 'Pending Approval',
            'record_date'     => now(),
            'approval_status' => 'pending',
            'medication_name' => $validated['medication_name'],
            'dosage'          => $validated['dosage'],
            'frequency'       => $validated['frequency']    ?? null,
            'duration_days'   => $validated['duration_days'] ?? null,
            'instructions'    => $validated['instructions']  ?? null,
        ];

        // Add prescription_image only if uploaded
        if (!empty($validated['prescription_image'])) {
            $data['prescription_image'] = $validated['prescription_image'];
        }

        // Only include quantity_requested if the column exists
        // Run: php artisan migrate  (2026_03_19_add_quantity_requested_to_health_records)
        if (Schema::hasColumn('health_records', 'quantity_requested')) {
            $data['quantity_requested'] = $validated['quantity_requested'] ?? null;
        }

        // Only include inventory_deducted if the column exists
        if (Schema::hasColumn('health_records', 'inventory_deducted')) {
            $data['inventory_deducted'] = false;
        }

        HealthRecord::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Medicine request submitted successfully! We will process it shortly.',
        ]);
    }

    public function index()
    {
        return redirect()->route('records.index');
    }
}