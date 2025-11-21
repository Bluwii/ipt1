@extends('layouts.app')

@section('title', 'Book Appointment - Tambubong Health Center')

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
    <div class="px-4 mx-auto max-w-6xl sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('appointments.store') }}" x-data="{ selectedService: '' }">
            @csrf
            
            <!-- Service Selection Section -->
            <div class="p-8 mb-8 bg-white shadow-md rounded-2xl">
                <!-- Back Button and Header -->
                <div class="mb-8">
                    <a href="{{ route('appointments.index') }}" class="inline-flex items-center gap-2 mb-6 text-blue-600 transition-colors hover:text-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="font-medium">Back</span>
                    </a>
                    <h2 class="text-2xl font-bold text-center text-gray-900 uppercase">Choose a Service to be Schedule</h2>
                </div>

                <!-- Service Cards Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Check Up Card -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="service_type" value="checkup" x-model="selectedService" class="sr-only peer" required>
                        <div class="p-8 text-center transition-all border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300 hover:shadow-lg">
                            <!-- Icon -->
                            <div class="flex items-center justify-center mb-10">
                                <div class="p-4 bg-blue-100 rounded-xl">
                                    <img src="{{ asset('image/bed.png') }}" alt="Check Up Icon" class="w-16 h-16 text-blue-600">
                                </div>
                            </div>
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900">Check Up</h3>
                            <!-- Check Icon (appears when selected) -->
                            <div class="absolute top-4 right-4">
                                <div class="items-center justify-center hidden w-6 h-6 text-white bg-blue-600 rounded-full peer-checked:flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Vaccine Card -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="service_type" value="vaccine" x-model="selectedService" class="sr-only peer" required>
                        <div class="p-8 text-center transition-all border-2 border-gray-200 rounded-2xl peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300 hover:shadow-lg">
                            <!-- Icon -->
                            <div class="flex items-center justify-center mb-10">
                                <div class="p-4 bg-red-100 rounded-xl">
                                    <img src="{{ asset('image/injection.png') }}" alt="Vaccine Icon" class="w-16 h-16 text-red-600">
                                </div>
                            </div>
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900">Vaccine</h3>
                            <!-- Check Icon (appears when selected) -->
                            <div class="absolute top-4 right-4">
                                <div class="items-center justify-center hidden w-6 h-6 text-white bg-red-600 rounded-full peer-checked:flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Request Medicine Card -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="service_type" value="medicine" x-model="selectedService" class="sr-only peer" required>
                        <div class="p-8 text-center transition-all border-2 border-gray-200 rounded-2xl peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-purple-300 hover:shadow-lg">
                            <!-- Icon -->
                            <div class="flex items-center justify-center mb-10">
                                <div class="p-4 bg-purple-100 rounded-xl">
                                    <img src="{{ asset('image/meds.png') }}" alt="Request Medicine Icon" class="w-16 h-16 text-purple-600">
                                </div>
                            </div>
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900">Request Medicine</h3>
                            <!-- Check Icon (appears when selected) -->
                            <div class="absolute top-4 right-4">
                                <div class="items-center justify-center hidden w-6 h-6 text-white bg-purple-600 rounded-full peer-checked:flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                @error('service_type')
                    <p class="mt-4 text-sm text-center text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Appointment Details Section (Shows after service selection) -->
            <div x-show="selectedService !== ''" x-cloak x-transition class="p-8 bg-white shadow-md rounded-2xl">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">Appointment Details</h2>
                
                <div class="space-y-6">
                    <!-- Date and Time Row -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Appointment Date -->
                        <div>
                            <label for="appointment_date" class="block mb-2 text-sm font-semibold text-gray-700">
                                Preferred Date <span class="text-red-600">*</span>
                            </label>
                            <input type="date" id="appointment_date" name="appointment_date" required 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            @error('appointment_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Appointment Time -->
                        <div>
                            <label for="appointment_time" class="block mb-2 text-sm font-semibold text-gray-700">
                                Preferred Time <span class="text-red-600">*</span>
                            </label>
                            <select id="appointment_time" name="appointment_time" required 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a time slot</option>
                                <option value="08:00">8:00 AM - 9:00 AM</option>
                                <option value="09:00">9:00 AM - 10:00 AM</option>
                                <option value="10:00">10:00 AM - 11:00 AM</option>
                                <option value="11:00">11:00 AM - 12:00 PM</option>
                                <option value="13:00">1:00 PM - 2:00 PM</option>
                                <option value="14:00">2:00 PM - 3:00 PM</option>
                                <option value="15:00">3:00 PM - 4:00 PM</option>
                                <option value="16:00">4:00 PM - 5:00 PM</option>
                            </select>
                            @error('appointment_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-semibold text-gray-700">
                            Additional Notes (Optional)
                        </label>
                        <textarea id="notes" name="notes" rows="4" 
                                  placeholder="Please provide any additional information or special requests..."
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Maximum 500 characters</p>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Important Information Box -->
                    <div class="p-6 border-l-4 border-blue-500 rounded-lg bg-blue-50">
                        <h3 class="mb-3 font-semibold text-blue-900">Important Information:</h3>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-start gap-2">
                                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Please arrive 10 minutes before your scheduled appointment
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Bring a valid ID and any relevant medical documents
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                For cancellations or rescheduling, notify us 24 hours in advance
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                For emergencies, please call 0991-275-1509
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Book Appointment
                        </button>
                        <a href="{{ route('appointments.index') }}" class="flex-1 px-6 py-3 font-semibold text-center text-gray-700 transition-colors border-2 border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection