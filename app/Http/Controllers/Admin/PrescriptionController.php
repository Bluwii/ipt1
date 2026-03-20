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

        // ── Resolve the actual quantity to deduct ──────────────────────────
        // Priority 1: quantity_requested column (from new medicine request form)
        // Priority 2: parse from title/notes as last resort
        // Never default to 1 blindly
        $qty = 0;

        if (Schema::hasColumn('health_records', 'quantity_requested') &&
            !is_null($record->quantity_requested) &&
            $record->quantity_requested > 0) {
            // ✅ Use the actual requested quantity
            $qty = (int) $record->quantity_requested;
        }

        $medicineName = $record->medication_name ?? null;

        // Only deduct if we have a valid medicine name AND quantity > 0
        if ($medicineName && $qty > 0 && Schema::hasTable('medicine_inventory')) {
            $inventoryItem = MedicineInventory::where('name', $medicineName)->first();

            if ($inventoryItem) {
                $stockBefore = $inventoryItem->stock;
                $inventoryItem->deduct($qty);
                $stockAfter = $inventoryItem->fresh()->stock;

                // Mark record as deducted
                $deductData = [];
                if (Schema::hasColumn('health_records', 'inventory_deducted')) {
                    $deductData['inventory_deducted'] = true;
                }
                if (Schema::hasColumn('health_records', 'inventory_deducted_at')) {
                    $deductData['inventory_deducted_at'] = now();
                }
                if (!empty($deductData)) {
                    $record->update($deductData);
                }

                $stockWarning = $stockAfter < 50
                    ? " ⚠️ Stock is now low ({$stockAfter} {$inventoryItem->unit} remaining)."
                    : '';

                return redirect()->back()->with('success',
                    "Approved! Deducted {$qty} pcs of {$medicineName} from inventory. " .
                    "Stock: {$stockBefore} → {$stockAfter}.{$stockWarning}"
                );
            } else {
                // Medicine not found in inventory — approve but warn
                return redirect()->back()->with('success',
                    "Request approved. Note: '{$medicineName}' was not found in inventory — stock was not deducted. Please add it to the inventory."
                );
            }
        }

        // Approved but no quantity to deduct (e.g. old requests before quantity_requested column)
        return redirect()->back()->with('success',
            'Medicine request approved. No inventory deduction (quantity not specified).'
        );
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