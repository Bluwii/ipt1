<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of health records.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all health records grouped by type
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
     * Display the specified health record.
     */
    public function show(HealthRecord $record): View
    {
        // Ensure the record belongs to the authenticated user
        if ($record->user_id !== Auth::user()->id) {
            abort(403);
        }
        
        return view('records.show', compact('record'));
    }
}