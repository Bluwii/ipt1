<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HealthRecordController extends Controller
{
    public function index(): View
    {
        $records = User::where('role', 'user')
            ->withCount('healthRecords')
            ->with(['healthRecords' => function ($query) {
                $query->orderBy('record_date', 'desc')->limit(1);
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($user, $index) {
                $lastRecord = $user->healthRecords->first();

                $age = 'N/A';
                if (!empty($user->birthdate)) {
                    try {
                        $age = \Carbon\Carbon::parse($user->birthdate)->age;
                    } catch (\Exception $e) {
                        $age = 'N/A';
                    }
                }

                return [
                    'no'           => $index + 1,
                    'id'           => $user->id,
                    'user_id'      => str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'patient_name' => $user->name,
                    'email'        => $user->email,
                    'age'          => $age,
                    'gender'       => ucfirst($user->gender ?? 'N/A'),
                    'records'      => $user->health_records_count,
                    'last_visit'   => $lastRecord
                        ? $lastRecord->record_date->format('M. d, Y')
                        : 'No visits',
                ];
            })->toArray();

        return view('admin.patient-records.index', compact('records'));
    }

    public function showPatient(User $user): View
    {
        $consultations = $user->healthRecords()
            ->where('record_type', 'consultation')
            ->orderBy('record_date', 'desc')->get();

        $vaccinations = $user->healthRecords()
            ->where('record_type', 'vaccination')
            ->orderBy('record_date', 'desc')->get();

        $prescriptions = $user->healthRecords()
            ->where('record_type', 'prescription')
            ->orderBy('record_date', 'desc')->get();

        $appointments = $user->appointments()
            ->orderBy('appointment_date', 'desc')->take(10)->get();

        return view('admin.patient-records.show', compact(
            'user', 'consultations', 'vaccinations', 'prescriptions', 'appointments'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'record_type'      => 'required|in:consultation,vaccination,prescription',
            'title'            => 'required|string|max:255',
            'provider_name'    => 'required|string|max:255',
            'record_date'      => 'required|date',
            'diagnosis'        => 'nullable|string',
            'notes'            => 'nullable|string',
            'blood_pressure'   => 'nullable|string|max:20',
            'temperature'      => 'nullable|numeric',
            'heart_rate'       => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'vaccine_name'     => 'nullable|string|max:255',
            'lot_number'       => 'nullable|string|max:100',
            'next_dose_date'   => 'nullable|date',
            'medication_name'  => 'nullable|string|max:255',
            'dosage'           => 'nullable|string|max:100',
            'frequency'        => 'nullable|string|max:100',
            'duration_days'    => 'nullable|integer',
            'instructions'     => 'nullable|string',
        ]);

        HealthRecord::create($validated);

        return redirect()->route('admin.patient-records.show-patient', $validated['user_id'])
            ->with('success', 'Health record added successfully!');
    }

    public function update(Request $request, HealthRecord $record): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'record_date'   => 'required|date',
            'diagnosis'     => 'nullable|string',
            'notes'         => 'nullable|string',
        ]);

        $record->update($validated);
        return redirect()->back()->with('success', 'Health record updated successfully!');
    }

    public function destroy(HealthRecord $record): RedirectResponse
    {
        $userId = $record->user_id;
        $record->delete();
        return redirect()->route('admin.patient-records.show-patient', $userId)
            ->with('success', 'Health record deleted successfully!');
    }
}