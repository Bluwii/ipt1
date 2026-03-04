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
     * List all prescription-type health records.
     */
    public function index(): View
    {
        $prescriptions = HealthRecord::where('record_type', 'prescription')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($record, $index) {
                $age = 'N/A';
                if ($record->user && isset($record->user->birthdate)) {
                    try {
                        $age = \Carbon\Carbon::parse($record->user->birthdate)->age;
                    } catch (\Exception $e) {}
                }

                return [
                    'no'                 => $index + 1,
                    'id'                 => $record->id,
                    'patient_name'       => $record->user->name ?? 'Unknown',
                    'age'                => $age,
                    'medicine_request'   => $record->medication_name ?? $record->title,
                    'dosage'             => $record->dosage ?? 'N/A',
                    'frequency'          => $record->frequency,
                    'duration_days'      => $record->duration_days,
                    'instructions'       => $record->instructions,
                    'prescription_image' => $record->prescription_image,
                    'approval_status'    => $record->approval_status ?? 'pending',
                    'admin_notes'        => $record->admin_notes,
                    'record_date'        => $record->record_date?->format('M d, Y') ?? '—',
                ];
            })->toArray();

        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show a single prescription record.
     */
    public function show(int $id): View
    {
        $record = HealthRecord::with('user')->findOrFail($id);
        return view('admin.prescriptions.show', compact('record'));
    }

    /**
     * Approve a prescription request.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        $record->update([
            'approval_status' => 'approved',
            'provider_name'   => Auth::user()->name,
            'admin_notes'     => $request->input('notes', 'Approved by admin'),
        ]);

        return redirect()->back()->with('success', 'Prescription approved successfully!');
    }

    /**
     * Reject a prescription request.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $record = HealthRecord::findOrFail($id);

        $record->update([
            'approval_status' => 'rejected',
            'admin_notes'     => $request->input('reason'),
        ]);

        return redirect()->back()->with('success', 'Prescription rejected.');
    }
}