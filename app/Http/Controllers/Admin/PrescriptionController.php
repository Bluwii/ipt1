<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescription requests.
     */
    public function index(): View
    {
        // Static prescription data
        $prescriptions = [
            [
                'no' => 1,
                'patient_name' => 'Joshua Clarence Berdaan',
                'age' => 23,
                'dose' => '850 mg',
                'medicine_request' => 'Cotrimoxazole',
            ],
            [
                'no' => 2,
                'patient_name' => 'Christian Tenedora',
                'age' => 30,
                'dose' => '60 mg',
                'medicine_request' => 'Ferrous sulfate',
            ],
            [
                'no' => 3,
                'patient_name' => 'Mekking Debi Cruz',
                'age' => 12,
                'dose' => '4 mg',
                'medicine_request' => 'Salbutamol',
            ],
            [
                'no' => 4,
                'patient_name' => 'Glo Bermudez',
                'age' => 45,
                'dose' => '200 mg',
                'medicine_request' => '500 mg/10',
            ],
            [
                'no' => 5,
                'patient_name' => 'Franchisekka Turao',
                'age' => 25,
                'dose' => '400 mg',
                'medicine_request' => 'Albendazole',
            ],
            [
                'no' => 6,
                'patient_name' => 'Meyvard Atienza',
                'age' => 38,
                'dose' => '10 mg',
                'medicine_request' => 'Nifedipine',
            ],
        ];
        
        return view('admin.prescriptions.index', compact('prescriptions'));
    }
    
    /**
     * Approve a prescription request.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        // Approval logic here
        
        return redirect()->back()
            ->with('success', 'Prescription approved successfully!');
    }
    
    /**
     * Reject a prescription request.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        // Rejection logic here
        
        return redirect()->back()
            ->with('success', 'Prescription rejected!');
    }
}