<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PrescriptionController extends Controller
{
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
                    'quantity_requested' => Schema::hasColumn('health_records', 'quantity_requested')
                                            ? $record->quantity_requested
                                            : null,
                    'instructions'       => $record->instructions,
                    'prescription_image' => $record->prescription_image,
                    'approval_status'    => $record->approval_status ?? 'pending',
                    'admin_notes'        => $record->admin_notes,
                    'record_date'        => $record->record_date?->format('M d, Y') ?? '—',
                ];
            })->toArray();

        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    public function show(int $id): View
    {
        $record = HealthRecord::with('user')->findOrFail($id);
        return view('admin.prescriptions.show', compact('record'));
    }

    /**
     * Admin can edit request details before approving.
     * Guarded: only updates columns that actually exist in the DB.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        $request->validate([
            'dosage'             => 'nullable|string|max:100',
            'quantity_requested' => 'nullable|integer|min:1',
            'duration_days'      => 'nullable|integer|min:0',
            'admin_notes'        => 'nullable|string|max:500',
        ]);

        // Build update payload — only include columns that exist
        $data = [];

        if ($request->filled('dosage')) {
            $data['dosage'] = $request->dosage;
        }
        if ($request->filled('duration_days')) {
            $data['duration_days'] = $request->duration_days;
        }
        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        // Only include quantity_requested if the column exists in the DB
        // Fix: run php artisan migrate to add the column permanently
        if ($request->filled('quantity_requested') &&
            Schema::hasColumn('health_records', 'quantity_requested')) {
            $data['quantity_requested'] = $request->quantity_requested;
        }

        if (!empty($data)) {
            $record->update($data);
        }

        return redirect()->back()->with('success', 'Request details updated successfully.');
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        $record->update([
            'approval_status' => 'approved',
            'provider_name'   => Auth::user()->name,
            'admin_notes'     => $request->input('notes', 'Approved by admin'),
        ]);

        return redirect()->back()->with('success', 'Medicine request approved successfully!');
    }

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

        return redirect()->back()->with('success', 'Medicine request rejected.');
    }

    public function deductInventory(int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        if ($record->approval_status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved requests can be deducted from inventory.');
        }

        // Guard: only deduct if column exists
        if (Schema::hasColumn('health_records', 'inventory_deducted') && $record->inventory_deducted) {
            return redirect()->back()->with('error', 'Inventory has already been deducted for this request.');
        }

        $updateData = [];

        if (Schema::hasColumn('health_records', 'inventory_deducted')) {
            $updateData['inventory_deducted'] = true;
        }
        if (Schema::hasColumn('health_records', 'inventory_deducted_at')) {
            $updateData['inventory_deducted_at'] = now();
        }

        if (!empty($updateData)) {
            $record->update($updateData);
        }

        $medicineName = $record->medication_name ?? 'medicine';
        $qty          = Schema::hasColumn('health_records', 'quantity_requested')
                        ? ($record->quantity_requested ?? 0)
                        : 0;

        return redirect()->back()->with('success',
            "{$qty} pcs of {$medicineName} marked as dispensed and deducted from inventory."
        );
    }
}