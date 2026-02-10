<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescription requests.
     */
    public function index(): View
    {
        // Get prescription-type health records from database WITH images
        $prescriptions = HealthRecord::where('record_type', 'prescription')
            ->with('user')
            ->orderBy('record_date', 'desc')
            ->get()
            ->map(function ($record, $index) {
                // Calculate age
                $age = 'N/A';
                if ($record->user && isset($record->user->birthdate)) {
                    try {
                        $age = \Carbon\Carbon::parse($record->user->birthdate)->age;
                    } catch (\Exception $e) {
                        $age = 'N/A';
                    }
                }
                
                return [
                    'no' => $index + 1,
                    'id' => $record->id,
                    'patient_name' => $record->user ? $record->user->name : 'Unknown',
                    'age' => $age,
                    'dose' => $record->dosage ?? 'N/A',
                    'medicine_request' => $record->medication_name ?? $record->title,
                    'prescription_image' => $record->prescription_image,
                    'approval_status' => $record->approval_status,
                    'admin_notes' => $record->admin_notes,
                    'frequency' => $record->frequency,
                    'duration_days' => $record->duration_days,
                    'instructions' => $record->instructions,
                ];
            })->toArray();
        
        return view('admin.prescriptions.index', compact('prescriptions'));
    }
    
    /**
     * Approve a prescription request.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $prescription = HealthRecord::findOrFail($id);
        
        $prescription->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'provider_name' => 'Dr. ' . Auth::user()->name, // Set actual provider
            'admin_notes' => $request->input('notes', 'Approved')
        ]);
        
        return redirect()->back()
            ->with('success', 'Prescription approved successfully!');
    }

    /**
     * Reject a prescription request.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $prescription = HealthRecord::findOrFail($id);
        
        $prescription->update([
            'approval_status' => 'rejected',
            'admin_notes' => $request->input('reason')
        ]);
        
        return redirect()->back()
            ->with('success', 'Prescription rejected!');
    }
}