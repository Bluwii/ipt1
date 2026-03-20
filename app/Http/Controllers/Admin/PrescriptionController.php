<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\MedicineInventory;
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

        // Load inventory from DB
        $inventory = collect();
        if (Schema::hasTable('medicine_inventory')) {
            $inventory = MedicineInventory::orderBy('category')
                ->orderBy('name')
                ->get();
        }

        return view('admin.prescriptions.index', compact('prescriptions', 'inventory'));
    }

    public function show(int $id): View
    {
        $record = HealthRecord::with('user')->findOrFail($id);
        $inventoryItem = Schema::hasTable('medicine_inventory')
            ? MedicineInventory::where('name', $record->medication_name)->first()
            : null;

        return view('admin.prescriptions.show', compact('record', 'inventoryItem'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        $request->validate([
            'dosage'             => 'nullable|string|max:100',
            'quantity_requested' => 'nullable|integer|min:1',
            'duration_days'      => 'nullable|integer|min:0',
            'admin_notes'        => 'nullable|string|max:500',
        ]);

        $data = [];

        if ($request->filled('dosage'))        $data['dosage']        = $request->dosage;
        if ($request->filled('duration_days')) $data['duration_days'] = $request->duration_days;
        if ($request->filled('admin_notes'))   $data['admin_notes']   = $request->admin_notes;

        if ($request->filled('quantity_requested') &&
            Schema::hasColumn('health_records', 'quantity_requested')) {
            $data['quantity_requested'] = (int) $request->quantity_requested;
        }

        if (!empty($data)) {
            $record->update($data);
        }

        return redirect()->back()->with('success', 'Request details updated successfully.');
    }

    /**
     * Approve — automatically deducts the CORRECT quantity from inventory.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        $record->update([
            'approval_status' => 'approved',
            'provider_name'   => Auth::user()->name,
            'admin_notes'     => $request->input('notes', 'Approved by admin'),
        ]);

        // Re-fetch so we have the latest saved quantity_requested
        $record = $record->fresh();

        $qty          = (int) ($record->quantity_requested ?? 0);
        $medicineName = $record->medication_name ?? null;

        if ($medicineName && $qty > 0 && Schema::hasTable('medicine_inventory')) {
            $inventoryItem = MedicineInventory::where('name', $medicineName)->first();

            if ($inventoryItem) {
                $stockBefore = $inventoryItem->stock;
                $inventoryItem->deduct($qty);
                $stockAfter = $inventoryItem->fresh()->stock;

                $this->markDeducted($record);

                $stockWarning = $stockAfter < 50
                    ? " ⚠️ Stock is now low ({$stockAfter} {$inventoryItem->unit} remaining)."
                    : '';

                return redirect()->back()->with('success',
                    "Approved! Deducted {$qty} pcs of {$medicineName}. " .
                    "Stock: {$stockBefore} → {$stockAfter}.{$stockWarning}"
                );
            }

            return redirect()->back()->with('success',
                "Request approved. Note: '{$medicineName}' was not found in inventory — stock was not deducted."
            );
        }

        return redirect()->back()->with('success',
            $qty === 0
                ? 'Medicine request approved. No inventory deduction (quantity not specified).'
                : 'Medicine request approved.'
        );
    }

    /**
     * Manually deduct inventory for an already-approved request.
     */
    public function deductInventory(int $id): RedirectResponse
    {
        $record = HealthRecord::findOrFail($id);

        if ($record->approval_status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved requests can be deducted.');
        }

        if ($record->inventory_deducted) {
            return redirect()->back()->with('error', 'Inventory already deducted for this request.');
        }

        $qty          = (int) ($record->quantity_requested ?? 0);
        $medicineName = $record->medication_name ?? null;

        if (!$medicineName || $qty <= 0) {
            return redirect()->back()->with('error', 'Cannot deduct: medicine name or quantity is missing.');
        }

        $inventoryItem = Schema::hasTable('medicine_inventory')
            ? MedicineInventory::where('name', $medicineName)->first()
            : null;

        if (!$inventoryItem) {
            return redirect()->back()->with('error', "'{$medicineName}' not found in inventory.");
        }

        $stockBefore = $inventoryItem->stock;
        $inventoryItem->deduct($qty);
        $stockAfter = $inventoryItem->fresh()->stock;

        $this->markDeducted($record);

        return redirect()->back()->with('success',
            "Deducted {$qty} pcs of {$medicineName}. Stock: {$stockBefore} → {$stockAfter}."
        );
    }

    private function markDeducted(HealthRecord $record): void
    {
        $data = [];
        if (Schema::hasColumn('health_records', 'inventory_deducted')) {
            $data['inventory_deducted'] = true;
        }
        if (Schema::hasColumn('health_records', 'inventory_deducted_at')) {
            $data['inventory_deducted_at'] = now();
        }
        if (!empty($data)) {
            $record->update($data);
        }
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $record = HealthRecord::findOrFail($id);
        $record->update([
            'approval_status' => 'rejected',
            'admin_notes'     => $request->input('reason'),
        ]);

        return redirect()->back()->with('success', 'Medicine request rejected.');
    }
}