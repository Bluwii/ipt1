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

        <!-- Health Summary Cards -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
            <div class="p-6 bg-white shadow-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Blood Type</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">O+</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C9.88 2 8.17 3.71 8.17 5.83C8.17 8.72 12 13 12 13S15.83 8.72 15.83 5.83C15.83 3.71 14.12 2 12 2Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white shadow-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Height</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">165 cm</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white shadow-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Weight</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">65 kg</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-6" x-data="{ activeTab: 'consultations' }">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button @click="activeTab = 'consultations'" 
                        :class="activeTab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Consultations
                </button>
                <button @click="activeTab = 'vaccinations'" 
                        :class="activeTab === 'vaccinations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Vaccinations
                </button>
                <button @click="activeTab = 'prescriptions'" 
                        :class="activeTab === 'prescriptions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2 whitespace-nowrap">
                    Prescriptions
                </button>
            </div>

            <!-- Consultations Tab -->
            <div x-show="activeTab === 'consultations'" class="py-6">
                <div class="space-y-4">
                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Annual Physical Examination</h3>
                                <p class="text-sm text-gray-600">Dr. Juan Dela Cruz</p>
                            </div>
                            <span class="text-sm text-gray-500">October 15, 2025</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Diagnosis:</span> Overall healthy condition</p>
                            <p><span class="font-medium">Blood Pressure:</span> 120/80 mmHg</p>
                            <p><span class="font-medium">Notes:</span> Regular exercise recommended. Follow-up in 6 months.</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">General Check-up</h3>
                                <p class="text-sm text-gray-600">Dr. Maria Santos</p>
                            </div>
                            <span class="text-sm text-gray-500">July 20, 2025</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Diagnosis:</span> Mild cold symptoms</p>
                            <p><span class="font-medium">Temperature:</span> 37.2°C</p>
                            <p><span class="font-medium">Notes:</span> Prescribed medication for 5 days. Rest and hydration advised.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vaccinations Tab -->
            <div x-show="activeTab === 'vaccinations'" class="py-6">
                <div class="space-y-4">
                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">COVID-19 Booster Shot</h3>
                                <p class="text-sm text-gray-600">Nurse Maria Santos</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Date:</span> September 15, 2025</p>
                            <p><span class="font-medium">Vaccine:</span> Pfizer-BioNTech</p>
                            <p><span class="font-medium">Lot Number:</span> FF1234</p>
                            <p><span class="font-medium">Next Dose:</span> Not required</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Influenza Vaccine</h3>
                                <p class="text-sm text-gray-600">Nurse Ana Cruz</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Date:</span> June 10, 2025</p>
                            <p><span class="font-medium">Vaccine:</span> Quadrivalent Influenza Vaccine</p>
                            <p><span class="font-medium">Lot Number:</span> FLU5678</p>
                            <p><span class="font-medium">Next Dose:</span> June 2026 (Annual)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescriptions Tab -->
            <div x-show="activeTab === 'prescriptions'" class="py-6">
                <div class="space-y-4">
                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Amoxicillin 500mg</h3>
                                <p class="text-sm text-gray-600">Prescribed by Dr. Maria Santos</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Active</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Date Prescribed:</span> July 20, 2025</p>
                            <p><span class="font-medium">Dosage:</span> 1 capsule, 3 times daily</p>
                            <p><span class="font-medium">Duration:</span> 5 days</p>
                            <p><span class="font-medium">Instructions:</span> Take after meals with full glass of water</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white shadow-md rounded-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Paracetamol 500mg</h3>
                                <p class="text-sm text-gray-600">Prescribed by Dr. Juan Dela Cruz</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Completed</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Date Prescribed:</span> June 15, 2025</p>
                            <p><span class="font-medium">Dosage:</span> 1 tablet every 6 hours as needed</p>
                            <p><span class="font-medium">Duration:</span> As needed for pain/fever</p>
                            <p><span class="font-medium">Instructions:</span> Do not exceed 4 tablets in 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection