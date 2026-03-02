<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_type',
        'title',
        'provider_name',
        'record_date',
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
        'prescription_image',
        'approval_status',
        'admin_notes',
        'source_appointment_id', // tracks which appointment auto-created this record
    ];

    protected $casts = [
        'record_date'    => 'date',
        'next_dose_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceAppointment()
    {
        return $this->belongsTo(Appointment::class, 'source_appointment_id');
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeConsultations($query)
    {
        return $query->where('record_type', 'consultation');
    }

    public function scopeVaccinations($query)
    {
        return $query->where('record_type', 'vaccination');
    }

    public function scopePrescriptions($query)
    {
        return $query->where('record_type', 'prescription');
    }
}