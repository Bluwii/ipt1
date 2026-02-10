<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'appointment_id',
        'record_type',
        'title',
        'provider_name',
        'diagnosis',
        'notes',
        'blood_pressure',
        'temperature',
        'heart_rate',
        'respiratory_rate',
        'vaccine_name',
        'lot_number',
        'next_dose_date',
        'medication_name',
        'dosage',
        'frequency',
        'duration_days',
        'instructions',
        'record_date',
        'prescription_image',
        'approval_status',
        'admin_notes',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'record_date' => 'date',
        'next_dose_date' => 'date',
        'temperature' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the health record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointment associated with the health record.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the record type label.
     */
    public function getRecordTypeLabelAttribute(): string
    {
        return match($this->record_type) {
            'consultation' => 'Consultation',
            'vaccination' => 'Vaccination',
            'prescription' => 'Prescription',
            default => 'Unknown',
        };
    }

    /**
     * Scope a query to only include consultations.
     */
    public function scopeConsultations($query)
    {
        return $query->where('record_type', 'consultation');
    }

    /**
     * Scope a query to only include vaccinations.
     */
    public function scopeVaccinations($query)
    {
        return $query->where('record_type', 'vaccination');
    }

    /**
     * Scope a query to only include prescriptions.
     */
    public function scopePrescriptions($query)
    {
        return $query->where('record_type', 'prescription');
    }

    /**
     * Scope a query to only include records for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}