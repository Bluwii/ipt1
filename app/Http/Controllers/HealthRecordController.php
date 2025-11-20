<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of health records.
     */
    public function index()
    {
        return view('records.index');
    }
}
