<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicineInventory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MedicineInventoryController extends Controller
{
    /**
     * Store a new medicine in inventory.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255|unique:medicine_inventory,name',
            'category' => 'nullable|string|max:100',
            'stock'    => 'required|integer|min:0',
            'unit'     => 'required|string|max:50',
        ]);

        MedicineInventory::create([
            'name'         => $request->name,
            'category'     => $request->category,
            'stock'        => $request->stock,
            'unit'         => $request->unit,
            'is_available' => true,
        ]);

        return redirect()->route('admin.prescriptions.index')
            ->with('success', "'{$request->name}' added to inventory.");
    }

    /**
     * Update stock count (for restocking).
     */
    public function update(Request $request, MedicineInventory $inventory): RedirectResponse
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $inventory->update(['stock' => $request->stock]);

        return redirect()->back()
            ->with('success', "Stock for '{$inventory->name}' updated to {$request->stock} {$inventory->unit}.");
    }

    /**
     * Toggle whether medicine is visible to patients.
     */
    public function toggleAvailable(MedicineInventory $inventory): RedirectResponse
    {
        $inventory->update(['is_available' => !$inventory->is_available]);

        $label = $inventory->is_available ? 'visible' : 'hidden';
        return redirect()->back()
            ->with('success', "'{$inventory->name}' is now {$label} to patients.");
    }
}