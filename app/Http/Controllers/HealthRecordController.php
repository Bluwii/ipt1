<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\MedicineInventory;
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

        // Backfill health records from completed appointments
        $this->backfillFromCompletedAppointments($user->id);

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

        // Load available medicines from inventory (DB-driven)
        // Falls back to empty collection if table doesn't exist yet
        $availableMedicines = collect();
        if (Schema::hasTable('medicine_inventory')) {
            $availableMedicines = MedicineInventory::available()
                ->orderBy('category')
                ->orderBy('name')
                ->get()
                ->groupBy('category');
        }

        return view('records.index', compact(
            'consultations',
            'vaccinations',
            'prescriptions',
            'availableMedicines'
        ));
    }

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
                $dateCarbon = $apt->appointment_date instanceof Carbon
                    ? $apt->appointment_date
                    : Carbon::parse($apt->appointment_date);

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
                    'checkup'  => 'Consultation – '        . $dateCarbon->format('M d, Y'),
                    'vaccine'  => 'Vaccination – '         . $dateCarbon->format('M d, Y'),
                    'medicine' => 'Prescription Request – ' . $dateCarbon->format('M d, Y'),
                    default    => 'Health Record – '        . $dateCarbon->format('M d, Y'),
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

                if ($hasSourceColumn) {
                    $payload['source_appointment_id'] = $apt->id;
                }

                HealthRecord::create($payload);

            } catch (\Throwable $e) {
                Log::warning("Health record backfill failed for appointment {$apt->id}: " . $e->getMessage());
            }
        }
    }
}