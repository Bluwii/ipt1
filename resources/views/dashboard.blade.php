@extends('layouts.app')

@section('title', 'Dashboard - Tambubong Health Center')

@section('content')

<!-- Custom Navigation for Dashboard -->
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
                    <svg class="w-4 h-4 text-gray-600 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            My Appointments
                        </a>
                        <a href="{{ route('records.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Health Records
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

        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="mt-2 text-gray-600">Here's what's happening with your health services today.</p>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center justify-between">
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="text-green-600 hover:text-green-800">✕</button>
            </div>
        </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
            <div class="p-6 transition-shadow bg-white shadow-md rounded-xl hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $upcomingCount }}</p>
                        <p class="mt-1 text-xs text-gray-500">Appointments</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6 transition-shadow bg-white shadow-md rounded-xl hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Health</p>
                        <p class="text-3xl font-bold text-green-600">{{ $totalRecords }}</p>
                        <p class="mt-1 text-xs text-gray-500">Records</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6 transition-shadow bg-white shadow-md rounded-xl hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $completedCount }}</p>
                        <p class="mt-1 text-xs text-gray-500">Appointments</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            <!-- Left Column (2/3) -->
            <div class="space-y-8 lg:col-span-2">

                <!-- Quick Actions -->
                <div class="p-6 bg-white shadow-md rounded-xl">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Quick Actions</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <button @click="window.dispatchEvent(new CustomEvent('open-appointment-modal'))" type="button"
                                class="flex flex-col items-center justify-center p-4 transition-all border-2 border-blue-200 rounded-lg hover:border-blue-500 hover:bg-blue-50">
                            <svg class="w-10 h-10 mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900">Book Appointment</span>
                        </button>

                        <a href="{{ route('records.index') }}"
                           class="flex flex-col items-center justify-center p-4 transition-all border-2 border-purple-200 rounded-lg hover:border-purple-500 hover:bg-purple-50">
                            <svg class="w-10 h-10 mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900">View Records</span>
                        </a>

                        <!-- Request Prescription shortcut -->
                        <a href="{{ route('records.index') }}#prescriptions"
                           class="flex flex-col items-center justify-center p-4 transition-all border-2 border-green-200 rounded-lg hover:border-green-500 hover:bg-green-50">
                            <svg class="w-10 h-10 mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900">Request Prescription</span>
                        </a>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Upcoming Appointments</h2>
                        <a href="{{ route('appointments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all →</a>
                    </div>

                    @if($upcomingAppointments->count() > 0)
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            @foreach($upcomingAppointments->take(2) as $appointment)
                                <div class="overflow-hidden bg-white shadow-md rounded-xl">
                                    <div class="p-6 border-l-4
                                        {{ $appointment->service_type === 'checkup' ? 'border-blue-500' : ($appointment->service_type === 'vaccine' ? 'border-green-500' : 'border-purple-500') }}">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 rounded-lg
                                                    {{ $appointment->service_type === 'checkup' ? 'bg-blue-100' : ($appointment->service_type === 'vaccine' ? 'bg-green-100' : 'bg-purple-100') }}">
                                                    <svg class="w-6 h-6 {{ $appointment->service_type === 'checkup' ? 'text-blue-600' : ($appointment->service_type === 'vaccine' ? 'text-green-600' : 'text-purple-600') }}"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900">{{ $appointment->service_type_label }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $appointment->full_name }}</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $appointment->status === 'confirmed' ? 'text-blue-800 bg-blue-100' : 'text-yellow-800 bg-yellow-100' }}">
                                                {{ $appointment->status_label }}
                                            </span>
                                        </div>

                                        <div class="space-y-2 text-sm text-gray-700">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $appointment->appointment_date->format('F d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center bg-white shadow-md rounded-xl">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">No Upcoming Appointments</h3>
                            <p class="mb-4 text-gray-600">You don't have any scheduled appointments yet.</p>
                            <button @click="window.dispatchEvent(new CustomEvent('open-appointment-modal'))"
                                    class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Book Appointment
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity (DYNAMIC) -->
                <div class="p-6 bg-white shadow-md rounded-xl">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Recent Activity</h2>
                    @if($recentActivity->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivity as $activity)
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full {{ $activity['bg_color'] }}">
                                <svg class="w-5 h-5 {{ $activity['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $activity['icon'] !!}
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500">No recent activity to display.</p>
                    @endif
                </div>
            </div>

            <!-- Right Column (1/3) -->
            <div class="space-y-8">

                <!-- Health Tips -->
                <div x-data="{ show: true }" x-show="show" x-cloak
                     class="relative p-6 text-white shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                    <button @click="show = false" class="absolute p-1 text-white rounded-lg top-2 right-2 hover:bg-white/20">✕</button>
                    <h2 class="mb-4 text-xl font-bold">Health Tip of the Day</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Drink at least 8 glasses of water daily to stay hydrated.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Get 7–9 hours of quality sleep each night for optimal health.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Health Center -->
                <div class="p-6 bg-white shadow-md rounded-xl">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Contact Health Center</h2>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Phone</p>
                                <p class="text-gray-600">0991-275-1509</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Address</p>
                                <p class="text-gray-600">118 Barangay Tambubong Rd, San Rafael, Bulacan</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Hours</p>
                                <p class="text-gray-600">Mon–Fri: 8:00 AM – 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Notice -->
                <div x-data="{ show: true }" x-show="show" x-cloak
                     class="relative p-6 border-l-4 border-red-500 rounded-lg bg-red-50">
                    <button @click="show = false" class="absolute p-1 text-red-600 rounded-lg top-2 right-2 hover:bg-red-100">✕</button>
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-900">Emergency</p>
                            <p class="mt-1 text-sm text-red-800">For medical emergencies, call 0991-275-1509 immediately or visit the nearest hospital.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.appointment-modal')
@endsection