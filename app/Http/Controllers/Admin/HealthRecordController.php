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
        // Get all users (patients) from database with their health records count and last visit
        $records = User::where('role', 'user')
            ->withCount('healthRecords')
            ->with(['healthRecords' => function ($query) {
                $query->orderBy('record_date', 'desc')->limit(1);
            }])
            ->get()
            ->map(function ($user, $index) {
                $lastRecord = $user->healthRecords->first();
                
                // Calculate age if birthdate exists
                $age = 'N/A';
                if (isset($user->birthdate)) {
                    try {
                        $age = \Carbon\Carbon::parse($user->birthdate)->age;
                    } catch (\Exception $e) {
                        $age = 'N/A';
                    }
                }
                
                return [
                    'user_id' => str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'patient_name' => $user->name,
                    'age' => $age,
                    'gender' => $user->gender ?? 'N/A',
                    'last_visit' => $lastRecord 
                        ? $lastRecord->record_date->format('M. d, Y') 
                        : 'No visits',
                ];
            })->toArray();
        
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