<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class DataRecordController extends Controller
{
    public function index(): View
    {
        // Generate month list for the current year
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create(null, $m, 1)->format('F'); // January, February...
        }

        return view('admin.data-records.index', compact('months'));
    }

    public function getMonthlyData(Request $request): JsonResponse
    {
        $monthName = $request->input('month');   // e.g. "January"
        $type      = $request->input('type');    // "records" | "medicines" | "age_visit"
        $year      = now()->year;

        // Convert month name to number
        $monthNumber = Carbon::parse("1 $monthName $year")->month;

        switch ($type) {

            // ─── Data Records For Each Month ───────────────────────────────────
            case 'records':
                // Count each service type for every day of the selected month
                $daysInMonth = Carbon::create($year, $monthNumber)->daysInMonth;
                $labels = [];
                $checkupData  = [];
                $vaccineData  = [];
                $medicineData = [];

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = Carbon::create($year, $monthNumber, $d)->toDateString();
                    $labels[] = $d;

                    $checkupData[]  = Appointment::where('service_type', 'checkup')
                        ->whereDate('appointment_date', $date)->count();
                    $vaccineData[]  = Appointment::where('service_type', 'vaccine')
                        ->whereDate('appointment_date', $date)->count();
                    $medicineData[] = Appointment::where('service_type', 'medicine')
                        ->whereDate('appointment_date', $date)->count();
                }

                return response()->json([
                    'title'     => "Data Records for $monthName $year",
                    'chartType' => 'bar',
                    'data'      => [
                        'labels'   => $labels,
                        'datasets' => [
                            [
                                'label'           => 'Consultation / Check-up',
                                'data'            => $checkupData,
                                'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                                'borderColor'     => 'rgba(59, 130, 246, 1)',
                                'borderWidth'     => 1,
                            ],
                            [
                                'label'           => 'Vaccination',
                                'data'            => $vaccineData,
                                'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                                'borderColor'     => 'rgba(34, 197, 94, 1)',
                                'borderWidth'     => 1,
                            ],
                            [
                                'label'           => 'Prescribe Medicine',
                                'data'            => $medicineData,
                                'backgroundColor' => 'rgba(168, 85, 247, 0.7)',
                                'borderColor'     => 'rgba(168, 85, 247, 1)',
                                'borderWidth'     => 1,
                            ],
                        ],
                    ],
                ]);

            // ─── Top Prescribed Medicines ───────────────────────────────────────
            case 'medicines':
                $medicines = HealthRecord::where('record_type', 'prescription')
                    ->whereMonth('record_date', $monthNumber)
                    ->whereYear('record_date', $year)
                    ->whereNotNull('medication_name')
                    ->selectRaw('medication_name, COUNT(*) as count')
                    ->groupBy('medication_name')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get();

                // Also count from appointments (medicine service type)
                $aptMedicines = Appointment::where('service_type', 'medicine')
                    ->whereMonth('appointment_date', $monthNumber)
                    ->whereYear('appointment_date', $year)
                    ->whereNotNull('notes')
                    ->count();

                if ($medicines->isEmpty()) {
                    // Fallback: just show appointment counts by week
                    $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    $data   = [];
                    for ($w = 1; $w <= 4; $w++) {
                        $start = Carbon::create($year, $monthNumber, (($w - 1) * 7) + 1);
                        $end   = $w < 4
                            ? $start->copy()->addDays(6)
                            : Carbon::create($year, $monthNumber)->endOfMonth();
                        $data[] = Appointment::where('service_type', 'medicine')
                            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
                            ->count();
                    }

                    return response()->json([
                        'title'     => "Prescription Appointments – $monthName $year",
                        'chartType' => 'bar',
                        'data'      => [
                            'labels'   => $labels,
                            'datasets' => [[
                                'label'           => 'Prescription Appointments',
                                'data'            => $data,
                                'backgroundColor' => [
                                    'rgba(34,197,94,0.7)',
                                    'rgba(59,130,246,0.7)',
                                    'rgba(168,85,247,0.7)',
                                    'rgba(249,115,22,0.7)',
                                ],
                                'borderWidth' => 1,
                            ]],
                        ],
                    ]);
                }

                return response()->json([
                    'title'     => "Top Prescribed Medicines – $monthName $year",
                    'chartType' => 'bar',
                    'data'      => [
                        'labels'   => $medicines->pluck('medication_name')->toArray(),
                        'datasets' => [[
                            'label'           => 'Times Prescribed',
                            'data'            => $medicines->pluck('count')->toArray(),
                            'backgroundColor' => [
                                'rgba(34,197,94,0.7)',
                                'rgba(59,130,246,0.7)',
                                'rgba(168,85,247,0.7)',
                                'rgba(249,115,22,0.7)',
                                'rgba(239,68,68,0.7)',
                                'rgba(20,184,166,0.7)',
                                'rgba(234,179,8,0.7)',
                                'rgba(99,102,241,0.7)',
                                'rgba(236,72,153,0.7)',
                                'rgba(14,165,233,0.7)',
                            ],
                            'borderWidth' => 1,
                        ]],
                    ],
                ]);

            // ─── Patient Age Visit Per Month ────────────────────────────────────
            case 'age_visit':
                // Group patients who had appointments this month by age bracket
                $appointmentsThisMonth = Appointment::with('user')
                    ->whereMonth('appointment_date', $monthNumber)
                    ->whereYear('appointment_date', $year)
                    ->whereNotNull('user_id')
                    ->get();

                $brackets = [
                    '0-12'  => 0,
                    '13-17' => 0,
                    '18-30' => 0,
                    '31-45' => 0,
                    '46-60' => 0,
                    '60+'   => 0,
                ];

                foreach ($appointmentsThisMonth as $apt) {
                    if (!$apt->user || !$apt->user->birthdate) continue;
                    try {
                        $age = Carbon::parse($apt->user->birthdate)->age;
                    } catch (\Exception $e) {
                        continue;
                    }

                    if ($age <= 12)       $brackets['0-12']++;
                    elseif ($age <= 17)   $brackets['13-17']++;
                    elseif ($age <= 30)   $brackets['18-30']++;
                    elseif ($age <= 45)   $brackets['31-45']++;
                    elseif ($age <= 60)   $brackets['46-60']++;
                    else                  $brackets['60+']++;
                }

                return response()->json([
                    'title'     => "Patient Age Visit – $monthName $year",
                    'chartType' => 'pie',
                    'data'      => [
                        'labels'   => array_keys($brackets),
                        'datasets' => [[
                            'label'           => 'Patients',
                            'data'            => array_values($brackets),
                            'backgroundColor' => [
                                'rgba(59,130,246,0.8)',
                                'rgba(34,197,94,0.8)',
                                'rgba(168,85,247,0.8)',
                                'rgba(249,115,22,0.8)',
                                'rgba(239,68,68,0.8)',
                                'rgba(20,184,166,0.8)',
                            ],
                            'borderWidth' => 2,
                            'borderColor' => '#fff',
                        ]],
                    ],
                ]);

            default:
                return response()->json(['error' => 'Invalid type'], 422);
        }
    }
}