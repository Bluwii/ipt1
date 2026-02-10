<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DataRecordController extends Controller
{
    /**
     * Display data records analytics page.
     */
    public function index(): View
    {
        $months = [
            'January', 'February', 'March', 'April', 
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];
        
        return view('admin.data-records.index', compact('months'));
    }
    
    /**
     * Get monthly data for specific month.
     */
    public function getMonthlyData(Request $request): JsonResponse
    {
        $month = $request->input('month');
        $type = $request->input('type'); // 'records', 'medicines', or 'age_visit'
        
        // Get the month number (1-12)
        $monthNumber = array_search($month, [
            'January', 'February', 'March', 'April', 
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ]) + 1;
        
        $year = now()->year;
        
        // Get real data based on type
        $data = $this->getRealMonthlyData($monthNumber, $year, $type);
        
        return response()->json($data);
    }
    
    /**
     * Get real monthly data based on month, year and type.
     * ONLY COUNTS APPROVED PRESCRIPTIONS
     */
    private function getRealMonthlyData(int $month, int $year, string $type): array
    {
        // Data Records For Each Month - REAL DATA
        if ($type === 'records') {
            $weeks = [
                ['start' => 1, 'end' => 7],
                ['start' => 8, 'end' => 14],
                ['start' => 15, 'end' => 21],
                ['start' => 22, 'end' => 31]
            ];
            
            $consultations = [];
            $vaccinations = [];
            $prescriptions = [];
            
            foreach ($weeks as $week) {
                $consultations[] = HealthRecord::where('record_type', 'consultation')
                    ->whereYear('record_date', $year)
                    ->whereMonth('record_date', $month)
                    ->whereDay('record_date', '>=', $week['start'])
                    ->whereDay('record_date', '<=', $week['end'])
                    ->count();
                
                $vaccinations[] = HealthRecord::where('record_type', 'vaccination')
                    ->whereYear('record_date', $year)
                    ->whereMonth('record_date', $month)
                    ->whereDay('record_date', '>=', $week['start'])
                    ->whereDay('record_date', '<=', $week['end'])
                    ->count();
                
                // ⭐ ONLY COUNT APPROVED PRESCRIPTIONS ⭐
                $prescriptions[] = HealthRecord::where('record_type', 'prescription')
                    ->where('approval_status', 'approved')
                    ->whereYear('record_date', $year)
                    ->whereMonth('record_date', $month)
                    ->whereDay('record_date', '>=', $week['start'])
                    ->whereDay('record_date', '<=', $week['end'])
                    ->count();
            }
            
            return [
                'title' => "Data Records For " . date('F', mktime(0, 0, 0, $month, 1)),
                'chartType' => 'bar',
                'data' => [
                    'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    'datasets' => [
                        [
                            'label' => 'Consultations',
                            'data' => $consultations,
                            'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                        ],
                        [
                            'label' => 'Vaccinations',
                            'data' => $vaccinations,
                            'backgroundColor' => 'rgba(75, 192, 192, 0.8)',
                        ],
                        [
                            'label' => 'Prescriptions (Approved)',
                            'data' => $prescriptions,
                            'backgroundColor' => 'rgba(153, 102, 255, 0.8)',
                        ],
                    ],
                ],
            ];
        }
        
        // Top Prescribed Medicines - ⭐ APPROVED ONLY ⭐
        if ($type === 'medicines') {
            $medicineData = HealthRecord::where('record_type', 'prescription')
                ->where('approval_status', 'approved')
                ->whereYear('record_date', $year)
                ->whereMonth('record_date', $month)
                ->whereNotNull('medication_name')
                ->selectRaw('medication_name, COUNT(*) as count')
                ->groupBy('medication_name')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
            
            $labels = $medicineData->pluck('medication_name')->toArray();
            $counts = $medicineData->pluck('count')->toArray();
            
            $totalOthers = HealthRecord::where('record_type', 'prescription')
                ->where('approval_status', 'approved')
                ->whereYear('record_date', $year)
                ->whereMonth('record_date', $month)
                ->whereNotNull('medication_name')
                ->whereNotIn('medication_name', $labels)
                ->count();
            
            if ($totalOthers > 0) {
                $labels[] = 'Others';
                $counts[] = $totalOthers;
            }
            
            if (empty($labels)) {
                $labels = ['No Data'];
                $counts = [1];
            }
            
            return [
                'title' => "Top Prescribed Medicines - " . date('F', mktime(0, 0, 0, $month, 1)),
                'chartType' => 'pie',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Prescriptions',
                            'data' => $counts,
                            'backgroundColor' => [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(201, 203, 207, 0.8)',
                            ],
                        ],
                    ],
                ],
            ];
        }
        
        // Patient Age Visit Per Month
        if ($type === 'age_visit') {
            $ageGroups = [
                '0-10' => [0, 10],
                '11-20' => [11, 20],
                '21-30' => [21, 30],
                '31-40' => [31, 40],
                '41-50' => [41, 50],
                '51-60' => [51, 60],
                '60+' => [61, 120]
            ];
            
            $visitCounts = [];
            
            $appointmentUserIds = \App\Models\Appointment::whereYear('appointment_date', $year)
                ->whereMonth('appointment_date', $month)
                ->pluck('user_id')
                ->unique();
            
            // ⭐ ONLY APPROVED PRESCRIPTIONS ⭐
            $prescriptionUserIds = HealthRecord::where('record_type', 'prescription')
                ->where('approval_status', 'approved')
                ->whereYear('record_date', $year)
                ->whereMonth('record_date', $month)
                ->pluck('user_id')
                ->unique();
            
            $userIds = $appointmentUserIds->merge($prescriptionUserIds)->unique();
            
            $users = \App\Models\User::whereIn('id', $userIds)
                ->whereNotNull('birthdate')
                ->get();
            
            foreach ($ageGroups as $label => $range) {
                $count = 0;
                
                foreach ($users as $user) {
                    try {
                        $age = \Carbon\Carbon::parse($user->birthdate)->age;
                        if ($age >= $range[0] && $age <= $range[1]) {
                            $count++;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
                
                $visitCounts[] = $count;
            }
            
            return [
                'title' => "Patient Age Visit - " . date('F', mktime(0, 0, 0, $month, 1)),
                'chartType' => 'line',
                'data' => [
                    'labels' => array_keys($ageGroups),
                    'datasets' => [
                        [
                            'label' => 'Number of Visits',
                            'data' => $visitCounts,
                            'borderColor' => 'rgba(75, 192, 192, 1)',
                            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                            'tension' => 0.4,
                            'fill' => true,
                        ],
                    ],
                ],
            ];
        }
        
        return [];
    }
}