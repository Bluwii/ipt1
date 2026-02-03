@extends('layouts.app')

@section('title', 'Health Records - Tambubong Health Center')

@section('content')
<!-- Custom Navigation -->
<nav class="fixed top-0 z-50 w-full bg-white shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-8 h-8 sm:w-10 sm:h-10">
                <span class="text-base font-bold text-gray-800 sm:text-xl lg:text-2xl">Tambubong Health Center</span>
            </div>

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button" class="flex items-center gap-3 px-4 py-2 transition-colors rounded-lg hover:bg-gray-100">
                    <div class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="hidden font-medium text-gray-900 sm:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-600 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            User Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="min-h-screen py-8 mt-16 bg-gray-50">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Health Records</h1>
            <p class="mt-2 text-gray-600">View your medical history and health information</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6" x-data="{ activeTab: 'consultations' }">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button @click="activeTab = 'consultations'" 
                        :class="activeTab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Consultations ({{ $consultations->count() }})
                </button>
                <button @click="activeTab = 'vaccinations'" 
                        :class="activeTab === 'vaccinations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Vaccinations ({{ $vaccinations->count() }})
                </button>
                <button @click="activeTab = 'prescriptions'" 
                        :class="activeTab === 'prescriptions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Prescriptions ({{ $prescriptions->count() }})
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
                                <div class="space-y-2 text-sm">
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
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
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
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Date:</span> {{ $record->record_date->format('F d, Y') }}</p>
                                    @if($record->vaccine_name)
                                        <p><span class="font-medium">Vaccine:</span> {{ $record->vaccine_name }}</p>
                                    @endif
                                    @if($record->lot_number)
                                        <p><span class="font-medium">Lot Number:</span> {{ $record->lot_number }}</p>
                                    @endif
                                    @if($record->next_dose_date)
                                        <p><span class="font-medium">Next Dose:</span> {{ $record->next_dose_date->format('F d, Y') }}</p>
                                    @else
                                        <p><span class="font-medium">Next Dose:</span> Not required</p>
                                    @endif
                                    @if($record->notes)
                                        <p class="mt-3 text-gray-600">{{ $record->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Vaccination Records</h3>
                        <p class="text-gray-600">You don't have any vaccination records yet.</p>
                    </div>
                @endif
            </div>

            <!-- Prescriptions Tab -->
            <div x-show="activeTab === 'prescriptions'" class="py-6">
                @if($prescriptions->count() > 0)
                    <div class="space-y-4">
                        @foreach($prescriptions as $record)
                            <div class="p-6 bg-white shadow-md rounded-xl">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $record->title }}</h3>
                                        <p class="text-sm text-gray-600">Prescribed by {{ $record->provider_name }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Completed</span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Date Prescribed:</span> {{ $record->record_date->format('F d, Y') }}</p>
                                    @if($record->dosage)
                                        <p><span class="font-medium">Dosage:</span> {{ $record->dosage }}</p>
                                    @endif
                                    @if($record->frequency)
                                        <p><span class="font-medium">Frequency:</span> {{ $record->frequency }}</p>
                                    @endif
                                    @if($record->duration_days)
                                        <p><span class="font-medium">Duration:</span> {{ $record->duration_days }} days</p>
                                    @endif
                                    @if($record->instructions)
                                        <p><span class="font-medium">Instructions:</span> {{ $record->instructions }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Prescription Records</h3>
                        <p class="text-gray-600">You don't have any prescription records yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection