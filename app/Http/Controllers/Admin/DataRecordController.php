<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        
        // Static data for each month and type
        $data = $this->getStaticMonthlyData($month, $type);
        
        return response()->json($data);
    }
    
    /**
     * Get static monthly data based on month and type.
     */
    private function getStaticMonthlyData(string $month, string $type): array
    {
        // Data Records For Each Month
        if ($type === 'records') {
            return [
                'title' => "Data Records For {$month}",
                'chartType' => 'bar',
                'data' => [
                    'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    'datasets' => [
                        [
                            'label' => 'Consultations',
                            'data' => [45, 52, 48, 55],
                            'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                        ],
                        [
                            'label' => 'Vaccinations',
                            'data' => [30, 35, 28, 32],
                            'backgroundColor' => 'rgba(75, 192, 192, 0.8)',
                        ],
                        [
                            'label' => 'Prescriptions',
                            'data' => [25, 28, 22, 30],
                            'backgroundColor' => 'rgba(153, 102, 255, 0.8)',
                        ],
                    ],
                ],
            ];
        }
        
        // Top Prescribed Medicines
        if ($type === 'medicines') {
            return [
                'title' => "Top Prescribed Medicines - {$month}",
                'chartType' => 'pie',
                'data' => [
                    'labels' => [
                        'Paracetamol',
                        'Amoxicillin',
                        'Cetirizine',
                        'Ibuprofen',
                        'Salbutamol',
                        'Others'
                    ],
                    'datasets' => [
                        [
                            'label' => 'Prescriptions',
                            'data' => [120, 85, 65, 45, 30, 55],
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
            return [
                'title' => "Patient Age Visit - {$month}",
                'chartType' => 'line',
                'data' => [
                    'labels' => ['0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '60+'],
                    'datasets' => [
                        [
                            'label' => 'Number of Visits',
                            'data' => [45, 65, 88, 92, 75, 55, 38],
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