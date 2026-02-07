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
    /**
     * Display a listing of health records.
     */
    public function index(): View
    {
        // Static patient records data
        $records = [
            [
                'user_id' => '001',
                'patient_name' => 'Adriyn Velasco',
                'age' => 27,
                'gender' => 'Male',
                'last_visit' => 'Feb. 25, 2024',
            ],
            [
                'user_id' => '002',
                'patient_name' => 'Bianca Santos',
                'age' => 32,
                'gender' => 'Female',
                'last_visit' => 'Mar. 15, 2024',
            ],
            [
                'user_id' => '003',
                'patient_name' => 'Carlos Manuel',
                'age' => 23,
                'gender' => 'Male',
                'last_visit' => 'Apr. 3, 2024',
            ],
            [
                'user_id' => '004',
                'patient_name' => 'Diane Ramirez',
                'age' => 40,
                'gender' => 'Female',
                'last_visit' => 'May 18, 2024',
            ],
            [
                'user_id' => '005',
                'patient_name' => 'Elijah Cruz',
                'age' => 35,
                'gender' => 'Male',
                'last_visit' => 'May 31, 2024',
            ],
            [
                'user_id' => '006',
                'patient_name' => 'Francine Delos',
                'age' => 29,
                'gender' => 'Female',
                'last_visit' => 'Jun. 7, 2024',
            ],
            [
                'user_id' => '007',
                'patient_name' => 'Nathaniel Solano',
                'age' => 28,
                'gender' => 'Male',
                'last_visit' => 'Jan. 8, 2025',
            ],
            [
                'user_id' => '008',
                'patient_name' => 'Sophia Lim',
                'age' => 26,
                'gender' => 'Female',
                'last_visit' => 'Jan. 16, 2025',
            ],
            [
                'user_id' => '009',
                'patient_name' => 'Raymond Garcia',
                'age' => 31,
                'gender' => 'Male',
                'last_visit' => 'Feb. 2, 2025',
            ],
            [
                'user_id' => '010',
                'patient_name' => 'Angelo Fernandez',
                'age' => 24,
                'gender' => 'Female',
                'last_visit' => 'Feb. 15, 2025',
            ],
            [
                'user_id' => '011',
                'patient_name' => 'Daniel Gonzalez',
                'age' => 33,
                'gender' => 'Male',
                'last_visit' => 'Feb. 22, 2025',
            ],
            [
                'user_id' => '012',
                'patient_name' => 'Patricia Bautista',
                'age' => 36,
                'gender' => 'Female',
                'last_visit' => 'Mar. 12, 2025',
            ],
            [
                'user_id' => '013',
                'patient_name' => 'Christian Reyes',
                'age' => 20,
                'gender' => 'Male',
                'last_visit' => 'Apr. 3, 2025',
            ],
        ];
        
        return view('admin.patient-records.index', compact('records'));
    }
    
    /**
     * Show the form for creating a new health record.
     */
    public function create(): View
    {
        $users = User::where('role', 'user')->get();
        return view('admin.health-records.create', compact('users'));
    }
    
    /**
     * Store a newly created health record.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation and store logic here
        
        return redirect()->route('admin.health-records.index')
            ->with('success', 'Health record created successfully!');
    }
    
    /**
     * Display the specified health record.
     */
    public function show(HealthRecord $record): View
    {
        return view('admin.health-records.show', compact('record'));
    }
    
    /**
     * Show the form for editing the specified health record.
     */
    public function edit(HealthRecord $record): View
    {
        $users = User::where('role', 'user')->get();
        return view('admin.health-records.edit', compact('record', 'users'));
    }
    
    /**
     * Update the specified health record.
     */
    public function update(Request $request, HealthRecord $record): RedirectResponse
    {
        // Validation and update logic here
        
        return redirect()->route('admin.health-records.index')
            ->with('success', 'Health record updated successfully!');
    }
    
    /**
     * Remove the specified health record.
     */
    public function destroy(HealthRecord $record): RedirectResponse
    {
        $record->delete();
        
        return redirect()->route('admin.health-records.index')
            ->with('success', 'Health record deleted successfully!');
    }
}