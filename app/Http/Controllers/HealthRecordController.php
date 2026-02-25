<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HealthRecordController extends Controller
{
    /**
     * Display the user's health records.
     */
    public function index(): View
    {
        $user = Auth::user();

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
}