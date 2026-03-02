<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Carbon\Carbon;

class HealthRecordController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // ── Backfill: create health records for any completed appointments ──
        $this->backfillFromCompletedAppointments($user->id);

        // ── Query the health_records table ──
        $consultations = HealthRecord::forUser($user->id)
            ->consultations()
            ->orderBy('record_date', 'desc')
            ->get();

        $vaccinations = HealthRecord::forUser($user->id)
            ->vaccinations()
            ->orderBy('record_date', 'desc')
            ->get();

        $prescriptions = HealthRecord::forUser($user->id)
            ->prescriptions()
            ->orderBy('record_date', 'desc')
            ->get();

        return view('records.index', compact('consultations', 'vaccinations', 'prescriptions'));
    }

    /**
     * For every completed appointment belonging to this user,
     * create a health record if one doesn't already exist.
     * Uses try/catch per record so one failure doesn't block the rest.
     */
    private function backfillFromCompletedAppointments(int $userId): void
    {
        $serviceToRecordType = [
            'checkup'  => 'consultation',
            'vaccine'  => 'vaccination',
            'medicine' => 'prescription',
        ];

        $hasSourceColumn = Schema::hasColumn('health_records', 'source_appointment_id');

        $completed = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->get();

        foreach ($completed as $apt) {
            $recordType = $serviceToRecordType[$apt->service_type] ?? null;
            if (!$recordType) continue;

            try {
                // Parse date safely regardless of whether it's cast as Carbon or a string
                $dateCarbon = $apt->appointment_date instanceof \Carbon\Carbon
                    ? $apt->appointment_date
                    : Carbon::parse($apt->appointment_date);

                // Check for duplicate — use source_appointment_id if column exists,
                // otherwise fall back to matching by user + date + type
                if ($hasSourceColumn) {
                    $exists = HealthRecord::where('user_id', $userId)
                        ->where('source_appointment_id', $apt->id)
                        ->exists();
                } else {
                    $exists = HealthRecord::where('user_id', $userId)
                        ->where('record_type', $recordType)
                        ->whereDate('record_date', $dateCarbon->toDateString())
                        ->exists();
                }

                if ($exists) continue;

                $title = match($apt->service_type) {
                    'checkup'  => 'Consultation – ' . $dateCarbon->format('M d, Y'),
                    'vaccine'  => 'Vaccination – '  . $dateCarbon->format('M d, Y'),
                    'medicine' => 'Prescription Request – ' . $dateCarbon->format('M d, Y'),
                    default    => 'Health Record – ' . $dateCarbon->format('M d, Y'),
                };

                $payload = [
                    'user_id'         => $userId,
                    'record_type'     => $recordType,
                    'title'           => $title,
                    'provider_name'   => 'Tambubong Health Center',
                    'record_date'     => $dateCarbon->toDateString(),
                    'notes'           => $apt->notes ?? null,
                    'approval_status' => $recordType === 'prescription' ? 'pending' : null,
                ];

                // Only set source_appointment_id if the column exists
                if ($hasSourceColumn) {
                    $payload['source_appointment_id'] = $apt->id;
                }

                HealthRecord::create($payload);

            } catch (\Throwable $e) {
                // Log error but don't break the page
                Log::warning("Health record backfill failed for appointment {$apt->id}: " . $e->getMessage());
            }
        }
    }
}