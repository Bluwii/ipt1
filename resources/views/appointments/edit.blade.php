@extends('layouts.app')

@section('title', 'Edit Appointment - Tambubong Health Center')

@section('content')

<!-- Navigation -->
<nav class="fixed top-0 z-50 w-full bg-white shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="object-contain w-8 h-8 sm:w-10 sm:h-10">
                <span class="text-base font-bold text-gray-800 sm:text-xl lg:text-2xl">Tambubong Health Center</span>
            </div>
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button"
                        class="flex items-center gap-3 px-4 py-2 transition-colors rounded-lg hover:bg-gray-100">
                    <div class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="hidden font-medium text-gray-900 sm:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-600 transition-transform" :class="{'rotate-180': open}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('appointments.index') }}"
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Appointments</a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center w-full gap-3 px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">
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
    <div class="max-w-2xl px-4 mx-auto">

        <!-- Back link -->
        <a href="{{ route('appointments.index') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Appointments
        </a>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Appointment</h1>
            <p class="mt-1 text-sm text-gray-500">Only <strong>pending</strong> appointments can be edited.</p>
        </div>

        {{-- Guard --}}
        @if($appointment->status !== 'pending')
            <div class="p-5 text-sm text-yellow-800 border border-yellow-200 rounded-xl bg-yellow-50">
                <p class="mb-1 font-semibold">Cannot Edit Appointment</p>
                This appointment has status <strong>{{ ucfirst($appointment->status) }}</strong> and can no longer be changed.
                <div class="mt-3">
                    <a href="{{ route('appointments.index') }}"
                       class="inline-block px-4 py-2 text-sm font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                        Go Back
                    </a>
                </div>
            </div>
        @else

        {{-- Current appointment info --}}
        <div class="p-4 mb-5 text-sm text-blue-800 border border-blue-200 rounded-xl bg-blue-50">
            <p class="mb-2 font-semibold">Current Appointment Details</p>
            <div class="space-y-1">
                <p>Service: <strong>{{ $appointment->service_type_label }}</strong></p>
                <p>Date: <strong>{{ $appointment->appointment_date->format('F d, Y') }}</strong></p>
                <p>Time: <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong></p>
                <p>Status: <strong class="text-yellow-700">Pending</strong></p>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 mb-5 text-sm text-red-700 border border-red-200 rounded-xl bg-red-50">
            <ul class="space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @php
            $selectedService = old('service_type', $appointment->service_type);
            $selectedTime    = old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i'));
        @endphp

        <form action="{{ route('appointments.update', $appointment) }}" method="POST"
              class="p-6 space-y-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
            @csrf
            @method('PATCH')

            {{-- ── Service Type ── --}}
            <div>
                <label class="block mb-3 text-sm font-semibold text-gray-700">
                    Service Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">

                    {{-- Check Up --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="service_type" value="checkup" class="sr-only peer"
                               {{ $selectedService === 'checkup' ? 'checked' : '' }}>
                        <div class="flex flex-col items-center p-4 text-center transition-all border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                            <div class="flex items-center justify-center w-12 h-12 mb-2 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-800">Check Up</p>
                            @if($selectedService === 'checkup')
                                <span class="mt-1 text-xs font-bold text-blue-600">✓ Selected</span>
                            @endif
                        </div>
                    </label>

                    {{-- Vaccine --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="service_type" value="vaccine" class="sr-only peer"
                               {{ $selectedService === 'vaccine' ? 'checked' : '' }}>
                        <div class="flex flex-col items-center p-4 text-center transition-all border-2 border-gray-200 rounded-xl hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50">
                            <div class="flex items-center justify-center w-12 h-12 mb-2 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-800">Vaccine</p>
                            @if($selectedService === 'vaccine')
                                <span class="mt-1 text-xs font-bold text-red-600">✓ Selected</span>
                            @endif
                        </div>
                    </label>

                    {{-- Request Medicine --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="service_type" value="medicine" class="sr-only peer"
                               {{ $selectedService === 'medicine' ? 'checked' : '' }}>
                        <div class="flex flex-col items-center p-4 text-center transition-all border-2 border-gray-200 rounded-xl hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50">
                            <div class="flex items-center justify-center w-12 h-12 mb-2 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-800">Request Medicine</p>
                            @if($selectedService === 'medicine')
                                <span class="mt-1 text-xs font-bold text-purple-600">✓ Selected</span>
                            @endif
                        </div>
                    </label>

                </div>
                @error('service_type')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Appointment Date ── --}}
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">
                    Appointment Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="appointment_date"
                       value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                       min="{{ now()->addDay()->format('Y-m-d') }}"
                       class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              @error('appointment_date') border-red-400 @enderror">
                @error('appointment_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Time Slot ── --}}
            <div>
                <label class="block mb-3 text-sm font-semibold text-gray-700">
                    Preferred Time Slot <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                    @foreach(['08:00' => '8:00 AM', '09:00' => '9:00 AM', '10:00' => '10:00 AM', '11:00' => '11:00 AM', '12:00' => '12:00 PM'] as $val => $lbl)
                    <label class="cursor-pointer">
                        <input type="radio" name="appointment_time" value="{{ $val }}" class="sr-only peer"
                               {{ $selectedTime === $val ? 'checked' : '' }}>
                        <div class="p-3 text-sm text-center text-gray-700 transition-all border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:font-semibold">
                            {{ $lbl }}
                            @if($selectedTime === $val)
                                <div class="mt-0.5 text-xs font-bold text-blue-600">✓</div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('appointment_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Notes ── --}}
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">
                    Additional Notes
                    <span class="font-normal text-gray-400">(optional)</span>
                </label>
                <textarea name="notes" rows="3"
                          placeholder="Any additional information for the health center..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $appointment->notes) }}</textarea>
            </div>

            {{-- ── Buttons ── --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('appointments.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>

        @endif
    </div>
</div>
@endsection