<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineInventory extends Model
{
    protected $table = 'medicine_inventory';

    protected $fillable = [
        'name',
        'category',
        'stock',
        'unit',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'stock'        => 'integer',
    ];

    /**
     * Status label based on stock level.
     */
    public function getStatusAttribute(): string
    {
        if ($this->stock <= 0)  return 'Out of Stock';
        if ($this->stock < 50) return 'Low Stock';
        return 'In Stock';
    }

    /**
     * Safely deduct stock — never goes below 0.
     */
    public function deduct(int $qty): void
    {
        $this->stock = max(0, $this->stock - $qty);
        $this->save();
    }

    /**
     * Scope: only medicines available for patient selection.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }
}