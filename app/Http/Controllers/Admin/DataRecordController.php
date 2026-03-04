<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class DataRecordController extends Controller
{
    public function index(): View
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create(null, $m, 1)->format('F');
        }

        return view('admin.data-records.index', compact('months'));
    }

    public function getMonthlyData(Request $request): JsonResponse
    {
        $monthName   = $request->input('month');
        $type        = $request->input('type');
        $year        = now()->year;
        $monthNumber = Carbon::parse("1 $monthName $year")->month;

        switch ($type) {

            // ─── Visit Records — single orange bar chart (daily totals) ────────
            case 'records':
                $daysInMonth = Carbon::create($year, $monthNumber)->daysInMonth;
                $labels      = [];
                $visitData   = [];

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date      = Carbon::create($year, $monthNumber, $d)->toDateString();
                    $labels[]  = $d;
                    $visitData[] = Appointment::whereDate('appointment_date', $date)->count();
                }

                return response()->json([
                    'title'     => "Visit Records for $monthName $year",
                    'chartType' => 'bar',
                    'data'      => [
                        'labels'   => $labels,
                        'datasets' => [
                            [
                                'label'           => 'Visit',
                                'data'            => $visitData,
                                'backgroundColor' => 'rgba(234, 88, 12, 0.8)',
                                'borderColor'     => 'rgba(234, 88, 12, 1)',
                                'borderWidth'     => 1,
                                'borderRadius'    => 3,
                            ],
                        ],
                    ],
                ]);

            // ─── Top Prescribed Medicines — PIE chart ──────────────────────────
            case 'medicines':
                $medicines = HealthRecord::where('record_type', 'prescription')
                    ->whereMonth('record_date', $monthNumber)
                    ->whereYear('record_date', $year)
                    ->whereNotNull('medication_name')
                    ->selectRaw('medication_name, COUNT(*) as count')
                    ->groupBy('medication_name')
                    ->orderByDesc('count')
                    ->limit(8)
                    ->get();

                if ($medicines->isEmpty()) {
                    // Fallback: count by appointment notes keywords
                    $labels = ['No Data'];
                    $data   = [1];
                    $colors = ['rgba(200,200,200,0.7)'];
                } else {
                    $labels = $medicines->pluck('medication_name')->toArray();
                    $data   = $medicines->pluck('count')->toArray();
                    $colors = [
                        'rgba(59,130,246,0.85)',
                        'rgba(234,88,12,0.85)',
                        'rgba(234,179,8,0.85)',
                        'rgba(34,197,94,0.85)',
                        'rgba(168,85,247,0.85)',
                        'rgba(239,68,68,0.85)',
                        'rgba(20,184,166,0.85)',
                        'rgba(99,102,241,0.85)',
                    ];
                }

                return response()->json([
                    'title'     => "Top Prescribed Medicines – $monthName $year",
                    'chartType' => 'pie',
                    'data'      => [
                        'labels'   => $labels,
                        'datasets' => [[
                            'label'           => 'Prescriptions',
                            'data'            => $data,
                            'backgroundColor' => $colors,
                            'borderColor'     => '#fff',
                            'borderWidth'     => 2,
                        ]],
                    ],
                ]);

            // ─── Patient Age Visit — DOUGHNUT chart ────────────────────────────
            case 'age_visit':
                $appointmentsThisMonth = Appointment::with('user')
                    ->whereMonth('appointment_date', $monthNumber)
                    ->whereYear('appointment_date', $year)
                    ->whereNotNull('user_id')
                    ->get();

                // Brackets matching the screenshot: 0-12, 13-25, 26-40, 41-60, 60+
                $brackets = [
                    '0-12'  => 0,
                    '13-25' => 0,
                    '26-40' => 0,
                    '41-60' => 0,
                    '60+'   => 0,
                ];

                foreach ($appointmentsThisMonth as $apt) {
                    if (!$apt->user || !$apt->user->birthdate) continue;
                    try {
                        $age = Carbon::parse($apt->user->birthdate)->age;
                    } catch (\Exception $e) {
                        continue;
                    }

                    if ($age <= 12)      $brackets['0-12']++;
                    elseif ($age <= 25)  $brackets['13-25']++;
                    elseif ($age <= 40)  $brackets['26-40']++;
                    elseif ($age <= 60)  $brackets['41-60']++;
                    else                 $brackets['60+']++;
                }

                return response()->json([
                    'title'     => "Patient Age Visit – $monthName $year",
                    'chartType' => 'doughnut',
                    'data'      => [
                        'labels'   => array_keys($brackets),
                        'datasets' => [[
                            'label'           => 'Patients',
                            'data'            => array_values($brackets),
                            'backgroundColor' => [
                                'rgba(239,68,68,0.85)',   // 0-12   red
                                'rgba(168,85,247,0.85)',  // 13-25  purple
                                'rgba(249,115,22,0.85)',  // 26-40  orange
                                'rgba(59,130,246,0.85)',  // 41-60  blue
                                'rgba(34,197,94,0.85)',   // 60+    green
                            ],
                            'borderColor'  => '#fff',
                            'borderWidth'  => 3,
                            'hoverOffset'  => 8,
                        ]],
                    ],
                ]);

            // ─── Walk-in vs Online Bookings — bar chart ────────────────────────
            case 'walkin_online':
                $daysInMonth = Carbon::create($year, $monthNumber)->daysInMonth;
                $labels      = [];
                $onlineData  = [];
                $walkinData  = [];

                $hasSource = \Illuminate\Support\Facades\Schema::hasColumn('appointments', 'booking_source');

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date      = Carbon::create($year, $monthNumber, $d)->toDateString();
                    $labels[]  = $d;

                    if ($hasSource) {
                        $onlineData[] = Appointment::whereDate('appointment_date', $date)
                            ->where('booking_source', 'online')->count();
                        $walkinData[] = Appointment::whereDate('appointment_date', $date)
                            ->where('booking_source', 'walk_in')->count();
                    } else {
                        // Fallback: user_id present = online booking, null = walk-in
                        $onlineData[] = Appointment::whereDate('appointment_date', $date)
                            ->whereNotNull('user_id')->count();
                        $walkinData[] = Appointment::whereDate('appointment_date', $date)
                            ->whereNull('user_id')->count();
                    }
                }

                return response()->json([
                    'title'     => "Walk-in vs Online Bookings – $monthName $year",
                    'chartType' => 'bar',
                    'summary'   => [
                        'online' => array_sum($onlineData),
                        'walkin' => array_sum($walkinData),
                    ],
                    'data'      => [
                        'labels'   => $labels,
                        'datasets' => [
                            [
                                'label'           => 'Online',
                                'data'            => $onlineData,
                                'backgroundColor' => 'rgba(59, 130, 246, 0.75)',
                                'borderColor'     => 'rgba(59, 130, 246, 1)',
                                'borderWidth'     => 1,
                                'borderRadius'    => 3,
                            ],
                            [
                                'label'           => 'Walk-in',
                                'data'            => $walkinData,
                                'backgroundColor' => 'rgba(249, 115, 22, 0.75)',
                                'borderColor'     => 'rgba(249, 115, 22, 1)',
                                'borderWidth'     => 1,
                                'borderRadius'    => 3,
                            ],
                        ],
                    ],
                ]);

            default:
                return response()->json(['error' => 'Invalid type'], 422);
        }
    }
}