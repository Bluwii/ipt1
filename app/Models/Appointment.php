<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type',
        'specific_service',      // ← NEW: the sub-option within each service type
        'appointment_date',
        'appointment_time',
        'first_name',
        'middle_initial',
        'last_name',
        'birthdate',
        'age',
        'gender',
        'email',
        'phone_number',
        'purok_no',
        'notes',
        'status',
        'admin_notes',
        'is_minor',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'guardian_consent',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'birthdate'        => 'date',
        'is_minor'         => 'boolean',
        'guardian_consent' => 'boolean',
    ];

    // ─────────────────────────────────────────────────────────────────────
    //  SERVICE OPTIONS — used by both the booking modal and admin views
    // ─────────────────────────────────────────────────────────────────────

    public static function serviceOptions(): array
    {
        return [
            'checkup' => [
                'General Check-up',
                'Prenatal Check-up',
                'Child Health / IMCI',
                'Family Planning Consultation',
                'Senior Citizen Check-up',
                'Blood Pressure Monitoring',
                'Blood Sugar Monitoring',
                'Postpartum Check-up',
                'TB DOTS Consultation',
            ],
            'vaccine' => [
                'BCG Vaccine',
                'Hepatitis B Vaccine',
                'OPV / IPV (Polio)',
                'Pentavalent Vaccine (DPT-HepB-Hib)',
                'Measles-Rubella (MR) Vaccine',
                'HPV Vaccine',
                'Influenza Vaccine',
                'Tetanus Toxoid (TT)',
                'COVID-19 Vaccine',
                'PCV (Pneumococcal) Vaccine',
            ],
            'medicine' => [
                'Paracetamol',
                'Amoxicillin',
                'Mefenamic Acid',
                'Cetirizine (Antihistamine)',
                'Amlodipine (Hypertension)',
                'Metformin (Diabetes)',
                'Ferrous Sulfate (Iron Supplement)',
                'Vitamin A',
                'Vitamin B Complex',
                'Multivitamins',
                'ORS (Oral Rehydration Salts)',
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    //  RELATIONSHIPS
    // ─────────────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  ACCESSORS
    // ─────────────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_initial ? $this->middle_initial . '. ' : '';
        return "{$this->first_name} {$middle}{$this->last_name}";
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return match ($this->service_type) {
            'checkup'  => 'Check Up',
            'vaccine'  => 'Vaccination',
            'medicine' => 'Medicine Request',
            default    => 'Unknown',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default     => 'Unknown',
        };
    }

    // ─────────────────────────────────────────────────────────────────────
    //  SCOPES
    // ─────────────────────────────────────────────────────────────────────

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');
    }

    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where('appointment_date', '<', now()->toDateString())
                ->orWhere('status', 'completed');
        })->orderBy('appointment_date', 'desc');
    }
}