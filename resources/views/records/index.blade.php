@extends('layouts.app')

@section('title', 'Health Records - Tambubong Health Center')

@section('content')
<!-- Custom Navigation -->
<nav class="fixed top-0 z-50 w-full bg-white shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="object-contain w-8 h-8 sm:w-10 sm:h-10">
                <span class="text-base font-bold text-gray-800 sm:text-xl lg:text-2xl">Tambubong Health Center</span>
            </div>
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 px-4 py-2 transition-colors rounded-lg hover:bg-gray-100">
                    <div class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="hidden font-medium text-gray-900 sm:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-600 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Appointments</a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="min-h-screen py-8 mt-16 bg-gray-50" x-data="{ prescriptionModal: false }">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Health Records</h1>
                <p class="mt-2 text-gray-600">View your medical history and health information</p>
            </div>
            <button @click="prescriptionModal = true"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-all hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Request Medicine
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
        @endif

        <!-- Tabs -->
        <div x-data="{ activeTab: window.location.hash === '#prescriptions' ? 'prescriptions' : 'consultations' }">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button @click="activeTab = 'consultations'"
                        :class="activeTab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                    Consultations ({{ $consultations->count() }})
                </button>
                <button @click="activeTab = 'vaccinations'"
                        :class="activeTab === 'vaccinations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                    Vaccinations ({{ $vaccinations->count() }})
                </button>
                <button @click="activeTab = 'prescriptions'"
                        id="prescriptions"
                        :class="activeTab === 'prescriptions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                    Medicine Requests ({{ $prescriptions->count() }})
                </button>
            </div>

            <!-- Consultations Tab -->
            <div x-show="activeTab === 'consultations'" class="py-6">
                @if($consultations->count() > 0)
                    <div class="space-y-4">
                        @foreach($consultations as $record)
                            <div class="p-6 bg-white shadow-md rounded-xl">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $record->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $record->provider_name }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $record->record_date->format('F d, Y') }}</span>
                                </div>
                                <div class="space-y-2 text-sm text-gray-700">
                                    @if($record->diagnosis)
                                        <p><span class="font-medium">Diagnosis:</span> {{ $record->diagnosis }}</p>
                                    @endif
                                    @if($record->blood_pressure)
                                        <p><span class="font-medium">Blood Pressure:</span> {{ $record->blood_pressure }}</p>
                                    @endif
                                    @if($record->temperature)
                                        <p><span class="font-medium">Temperature:</span> {{ $record->temperature }}°C</p>
                                    @endif
                                    @if($record->heart_rate)
                                        <p><span class="font-medium">Heart Rate:</span> {{ $record->heart_rate }} bpm</p>
                                    @endif
                                    @if($record->notes)
                                        <p><span class="font-medium">Notes:</span> {{ $record->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Consultation Records</h3>
                        <p class="text-gray-600">You don't have any consultation records yet.</p>
                    </div>
                @endif
            </div>

            <!-- Vaccinations Tab -->
            <div x-show="activeTab === 'vaccinations'" class="py-6">
                @if($vaccinations->count() > 0)
                    <div class="space-y-4">
                        @foreach($vaccinations as $record)
                            <div class="p-6 bg-white shadow-md rounded-xl">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $record->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $record->provider_name }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                                </div>
                                <div class="space-y-2 text-sm text-gray-700">
                                    <p><span class="font-medium">Date:</span> {{ $record->record_date->format('F d, Y') }}</p>
                                    @if($record->vaccine_name)
                                        <p><span class="font-medium">Vaccine:</span> {{ $record->vaccine_name }}</p>
                                    @endif
                                    @if($record->lot_number)
                                        <p><span class="font-medium">Lot Number:</span> {{ $record->lot_number }}</p>
                                    @endif
                                    @if($record->next_dose_date)
                                        <p><span class="font-medium">Next Dose:</span> {{ $record->next_dose_date->format('F d, Y') }}</p>
                                    @endif
                                    @if($record->notes)
                                        <p class="text-gray-600">{{ $record->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Vaccination Records</h3>
                        <p class="text-gray-600">You don't have any vaccination records yet.</p>
                    </div>
                @endif
            </div>

            <!-- Medicine Requests Tab -->
            <div x-show="activeTab === 'prescriptions'" class="py-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">Your medicine requests submitted to the health center.</p>
                    <button @click="prescriptionModal = true"
                            class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                        + New Request
                    </button>
                </div>

                @if($prescriptions->count() > 0)
                    <div class="space-y-4">
                        @foreach($prescriptions as $record)
                            <div class="p-6 bg-white shadow-md rounded-xl">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $record->medication_name ?? $record->title }}</h3>
                                            <p class="text-sm text-gray-500">Submitted {{ $record->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($record->prescription_image)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                With Rx
                                            </span>
                                        @endif
                                        @if($record->approval_status === 'approved')
                                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✓ Approved</span>
                                        @elseif($record->approval_status === 'rejected')
                                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">✗ Rejected</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm text-gray-700 sm:grid-cols-4">
                                    @if($record->dosage)
                                        <div class="p-2 rounded-lg bg-gray-50">
                                            <p class="text-xs font-medium text-gray-500">Dosage</p>
                                            <p class="font-semibold">{{ $record->dosage }}</p>
                                        </div>
                                    @endif
                                    @if($record->frequency)
                                        <div class="p-2 rounded-lg bg-gray-50">
                                            <p class="text-xs font-medium text-gray-500">Frequency</p>
                                            <p class="font-semibold">{{ $record->frequency }}</p>
                                        </div>
                                    @endif
                                    @if($record->duration_days)
                                        <div class="p-2 rounded-lg bg-gray-50">
                                            <p class="text-xs font-medium text-gray-500">Duration</p>
                                            <p class="font-semibold">{{ $record->duration_days }} days</p>
                                        </div>
                                    @endif
                                    @if($record->quantity_requested)
                                        <div class="p-2 rounded-lg bg-gray-50">
                                            <p class="text-xs font-medium text-gray-500">Quantity</p>
                                            <p class="font-semibold">{{ $record->quantity_requested }} pcs</p>
                                        </div>
                                    @endif
                                </div>
                                @if($record->instructions)
                                    <p class="mt-3 text-sm text-gray-600"><span class="font-medium">Instructions:</span> {{ $record->instructions }}</p>
                                @endif
                                @if($record->admin_notes && $record->approval_status !== 'pending')
                                    <div class="p-3 mt-3 rounded-lg {{ $record->approval_status === 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                        <p class="text-xs font-semibold mb-0.5 {{ $record->approval_status === 'approved' ? 'text-green-700' : 'text-red-700' }}">Admin Note:</p>
                                        <p class="text-sm {{ $record->approval_status === 'approved' ? 'text-green-800' : 'text-red-800' }}">{{ $record->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Medicine Requests</h3>
                        <p class="mb-4 text-gray-600">Request medicine from the health center's available stock.</p>
                        <button @click="prescriptionModal = true"
                                class="px-6 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Request Medicine
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ===================== MEDICINE REQUEST MODAL ===================== -->
    <div x-show="prescriptionModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="prescriptionModal = false">

        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity bg-black bg-opacity-50"
             @click="prescriptionModal = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-xl overflow-hidden bg-white shadow-2xl rounded-2xl"
                 @click.outside="prescriptionModal = false"
                 x-data="medicineRequestForm()">

                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Request Medicine</h3>
                                <p class="text-xs text-blue-200">Select from available health center stock</p>
                            </div>
                        </div>
                        <button @click="prescriptionModal = false"
                                class="p-2 transition-colors rounded-lg text-white/70 hover:text-white hover:bg-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Patient Info Banner (auto-filled) -->
                <div class="px-6 py-3 border-b border-blue-100 bg-blue-50">
                    <div class="flex items-center gap-2 text-sm text-blue-800">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Requesting as: <strong>{{ Auth::user()->name }}</strong></span>
                        <span class="text-blue-400">•</span>
                        <span class="text-blue-600">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <!-- Form Body -->
                <form id="medicineRequestForm" class="p-6 space-y-5" @submit.prevent="submitRequest()">
                    @csrf

                    <!-- Medicine Selection -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                            Select Medicine <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="medication_name" x-model="selectedMedicine" required
                                    class="w-full py-3 pl-4 pr-10 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Select medicine --</option>
                                @php
                                    // Medicine inventory — admin can manage these
                                    $medicines = [
                                        ['name' => 'Paracetamol 500mg', 'category' => 'Pain Relief / Fever'],
                                        ['name' => 'Mefenamic Acid 500mg', 'category' => 'Pain Relief'],
                                        ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotic'],
                                        ['name' => 'Amoxicillin 250mg (Syrup)', 'category' => 'Antibiotic'],
                                        ['name' => 'Cetirizine 10mg', 'category' => 'Antihistamine'],
                                        ['name' => 'Loratadine 10mg', 'category' => 'Antihistamine'],
                                        ['name' => 'Amlodipine 5mg', 'category' => 'Hypertension'],
                                        ['name' => 'Amlodipine 10mg', 'category' => 'Hypertension'],
                                        ['name' => 'Losartan 50mg', 'category' => 'Hypertension'],
                                        ['name' => 'Metformin 500mg', 'category' => 'Diabetes'],
                                        ['name' => 'Glibenclamide 5mg', 'category' => 'Diabetes'],
                                        ['name' => 'Ferrous Sulfate 325mg', 'category' => 'Supplement'],
                                        ['name' => 'Vitamin A 10,000 IU', 'category' => 'Vitamin / Supplement'],
                                        ['name' => 'Vitamin B Complex', 'category' => 'Vitamin / Supplement'],
                                        ['name' => 'Multivitamins', 'category' => 'Vitamin / Supplement'],
                                        ['name' => 'Ascorbic Acid 500mg', 'category' => 'Vitamin / Supplement'],
                                        ['name' => 'ORS (Oral Rehydration Salts)', 'category' => 'Rehydration'],
                                        ['name' => 'Salbutamol 2mg', 'category' => 'Respiratory'],
                                        ['name' => 'Cotrimoxazole 400/80mg', 'category' => 'Antibiotic'],
                                        ['name' => 'Omeprazole 20mg', 'category' => 'Gastrointestinal'],
                                        ['name' => 'Antacid (Aluminum Hydroxide)', 'category' => 'Gastrointestinal'],
                                        ['name' => 'Ibuprofen 200mg', 'category' => 'Pain Relief / Anti-inflammatory'],
                                    ];
                                    $categories = collect($medicines)->groupBy('category');
                                @endphp
                                @foreach($categories as $category => $items)
                                    <optgroup label="{{ $category }}">
                                        @foreach($items as $med)
                                            <option value="{{ $med['name'] }}">{{ $med['name'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Dosage & Quantity Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                Dosage <span class="text-red-500">*</span>
                            </label>
                            <select name="dosage" x-model="dosage" required
                                    class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select dosage</option>
                                <option>1 tablet daily</option>
                                <option>1 tablet twice daily</option>
                                <option>1 tablet 3x daily</option>
                                <option>½ tablet daily</option>
                                <option>As needed (PRN)</option>
                                <option>As directed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <select name="quantity_requested" required
                                    class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select qty</option>
                                <option value="10">10 pcs</option>
                                <option value="20">20 pcs</option>
                                <option value="30">30 pcs</option>
                                <option value="60">60 pcs</option>
                                <option value="90">90 pcs (3-month supply)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Frequency & Duration Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Frequency</label>
                            <select name="frequency"
                                    class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select frequency</option>
                                <option>Once daily</option>
                                <option>Twice daily</option>
                                <option>Three times daily</option>
                                <option>Four times daily</option>
                                <option>Every 6 hours</option>
                                <option>Every 8 hours</option>
                                <option>As needed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Duration (days)</label>
                            <select name="duration_days"
                                    class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select duration</option>
                                <option value="3">3 days</option>
                                <option value="5">5 days</option>
                                <option value="7">7 days</option>
                                <option value="14">14 days</option>
                                <option value="30">30 days (1 month)</option>
                                <option value="90">90 days (3 months)</option>
                                <option value="0">Ongoing / Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <!-- Purpose / Notes -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                            Purpose / Condition <span class="font-normal text-gray-400">(optional)</span>
                        </label>
                        <textarea name="instructions" rows="2" maxlength="300"
                                  class="block w-full px-4 py-3 text-sm border border-gray-300 resize-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="e.g. For hypertension maintenance, diabetes management..."></textarea>
                    </div>

                    <!-- Prescription Image (OPTIONAL) -->
                    <div class="p-4 border-2 border-gray-200 border-dashed rounded-xl bg-gray-50">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="text-sm font-semibold text-gray-700">Doctor's Prescription</label>
                                    <span class="px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Recommended</span>
                                    <span class="text-xs font-normal text-gray-400">Optional</span>
                                </div>
                                <p class="mb-3 text-xs text-gray-500">Uploading a valid prescription from your doctor helps us serve you faster and with the correct dosage. Requests with a prescription are processed with priority.</p>
                                <input type="file" name="prescription_image" id="prescriptionImageInput"
                                       accept="image/jpeg,image/png,image/jpg"
                                       @change="handleFileChange($event)"
                                       class="block w-full text-sm text-gray-500 cursor-pointer file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                <p class="mt-1 text-xs text-gray-400">JPG or PNG, max 5MB</p>

                                <!-- Preview -->
                                <div x-show="previewUrl" class="relative inline-block mt-3">
                                    <img :src="previewUrl" class="object-cover h-20 border border-gray-200 rounded-lg">
                                    <button type="button" @click="clearFile()"
                                            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600">✕</button>
                                    <p class="mt-1 text-xs font-medium text-green-600">✓ Prescription attached</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error area -->
                    <div id="medicineRequestError" class="hidden p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-xl"></div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                        <button type="button" @click="prescriptionModal = false"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="medicineSubmitBtn"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 disabled:opacity-50 transition-colors shadow-sm">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function medicineRequestForm() {
    return {
        selectedMedicine: '',
        dosage: '',
        previewUrl: null,
        fileInput: null,

        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) { this.previewUrl = null; return; }
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be under 5MB.');
                event.target.value = '';
                this.previewUrl = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => { this.previewUrl = e.target.result; };
            reader.readAsDataURL(file);
        },

        clearFile() {
            this.previewUrl = null;
            const input = document.getElementById('prescriptionImageInput');
            if (input) input.value = '';
        },

        async submitRequest() {
            const form    = document.getElementById('medicineRequestForm');
            const btn     = document.getElementById('medicineSubmitBtn');
            const errDiv  = document.getElementById('medicineRequestError');

            btn.disabled = true;
            btn.textContent = 'Submitting…';
            errDiv.classList.add('hidden');

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("prescriptions.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    const messages = data.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : (data.message || 'Submission failed. Please try again.');
                    errDiv.textContent = messages;
                    errDiv.classList.remove('hidden');
                }
            } catch (err) {
                errDiv.textContent = 'An unexpected error occurred. Please try again.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Submit Request';
            }
        }
    }
}
</script>
@endsection