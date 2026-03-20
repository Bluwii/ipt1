{{-- resources/views/components/appointment-modal.blade.php --}}

@php
// Service sub-options for Check Up and Vaccine only
$serviceOptions = [
    'checkup' => [
        'General Check-up',
        'Prenatal Check-up',
        'Child Health / IMCI',
        'Family Planning Consultation',
        'Senior Citizen Check-up',
        'Blood Pressure Monitoring',
        'Blood Sugar Monitoring',
        'Postpartum Check-up',
        'TB DOTS Consultation',
    ],
    'vaccine' => [
        'BCG Vaccine',
        'Hepatitis B Vaccine',
        'OPV / IPV (Polio)',
        'Pentavalent Vaccine (DPT-HepB-Hib)',
        'Measles-Rubella (MR) Vaccine',
        'HPV Vaccine',
        'Influenza Vaccine',
        'Tetanus Toxoid (TT)',
        'COVID-19 Vaccine',
        'PCV (Pneumococcal) Vaccine',
    ],
    // Medicine has NO sub-options — goes directly to date/time
];

// Medicine list for the Step 3 medicine request form
$medicineList = [
    'Pain Relief / Fever'             => ['Paracetamol 500mg', 'Mefenamic Acid 500mg', 'Ibuprofen 200mg'],
    'Antibiotic'                      => ['Amoxicillin 500mg', 'Amoxicillin 250mg (Syrup)', 'Cotrimoxazole 400/80mg'],
    'Antihistamine'                   => ['Cetirizine 10mg', 'Loratadine 10mg'],
    'Hypertension'                    => ['Amlodipine 5mg', 'Amlodipine 10mg', 'Losartan 50mg'],
    'Diabetes'                        => ['Metformin 500mg', 'Glibenclamide 5mg'],
    'Vitamin / Supplement'            => ['Ferrous Sulfate 325mg', 'Vitamin A 10,000 IU', 'Vitamin B Complex', 'Multivitamins', 'Ascorbic Acid 500mg'],
    'Rehydration'                     => ['ORS (Oral Rehydration Salts)'],
    'Respiratory'                     => ['Salbutamol 2mg'],
    'Gastrointestinal'                => ['Omeprazole 20mg', 'Antacid (Aluminum Hydroxide)'],
];
@endphp

<div x-data="appointmentModal()" x-show="open" x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @open-appointment-modal.window="openModal()"
     @keydown.escape.window="closeModal()">

    {{-- Backdrop --}}
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
         @click="closeModal()"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative inline-block w-full max-w-5xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Schedule Appointment</h2>
                <button @click="closeModal()" class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Progress Indicator --}}
            <div class="px-6 pt-4 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    {{-- Step 1 --}}
                    <div class="flex items-center" :class="step >= 1 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">1</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Service</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    {{-- Step 2 --}}
                    <div class="flex items-center" :class="step >= 2 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">2</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Schedule</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    {{-- Step 3 --}}
                    <div class="flex items-center" :class="step >= 3 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">3</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline"
                              x-text="selectedService === 'medicine' ? 'Medicine Request' : 'Details'"></span>
                    </div>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 overflow-y-auto max-h-[calc(100vh-250px)]">

                {{-- ══════════════ STEP 1: Service Selection ══════════════ --}}
                <div x-show="step === 1" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Choose a Service</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        {{-- Check Up --}}
                        <button @click="selectService('checkup')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'checkup' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-blue-100 rounded-xl">
                                    <img src="{{ asset('image/bed.png') }}" alt="Check Up" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Check Up</h3>
                            <p class="mt-1 text-sm text-gray-500">Consultations & monitoring</p>
                            <div x-show="selectedService === 'checkup'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-blue-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- Vaccine --}}
                        <button @click="selectService('vaccine')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'vaccine' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-red-100 rounded-xl">
                                    <img src="{{ asset('image/injection.png') }}" alt="Vaccine" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Vaccine</h3>
                            <p class="mt-1 text-sm text-gray-500">Immunization services</p>
                            <div x-show="selectedService === 'vaccine'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-red-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- Request Medicine — no sub-option, goes straight to Step 2 --}}
                        <button @click="selectService('medicine')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'medicine' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-purple-100 rounded-xl">
                                    <img src="{{ asset('image/meds.png') }}" alt="Medicine" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Request Medicine</h3>
                            <p class="mt-1 text-sm text-gray-500">Medication assistance</p>
                            {{-- Medicine badge: skips specific service selection --}}
                            <div class="mt-2">
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    Select date &amp; fill form →
                                </span>
                            </div>
                            <div x-show="selectedService === 'medicine'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-purple-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>

                    {{-- Specific Service Dropdown — shown ONLY for checkup and vaccine --}}
                    <div x-show="selectedService && selectedService !== 'medicine'" x-transition
                         class="mt-6">
                        <div class="p-5 border-2 rounded-xl"
                             :class="{
                                 'border-blue-200 bg-blue-50':  selectedService === 'checkup',
                                 'border-red-200 bg-red-50':    selectedService === 'vaccine'
                             }">
                            <label class="block mb-2 text-sm font-bold"
                                   :class="{
                                       'text-blue-800': selectedService === 'checkup',
                                       'text-red-800':  selectedService === 'vaccine'
                                   }">
                                <span x-show="selectedService === 'checkup'">Select Consultation Type <span class="text-red-500">*</span></span>
                                <span x-show="selectedService === 'vaccine'">Select Vaccine <span class="text-red-500">*</span></span>
                            </label>

                            <select x-model="specificService"
                                    class="w-full px-4 py-3 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                    :class="{
                                        'focus:ring-blue-500': selectedService === 'checkup',
                                        'focus:ring-red-500':  selectedService === 'vaccine'
                                    }">
                                <option value="">-- Please select --</option>

                                {{-- Checkup options --}}
                                <template x-if="selectedService === 'checkup'">
                                    <template x-for="opt in serviceOptions.checkup" :key="opt">
                                        <option :value="opt" x-text="opt"></option>
                                    </template>
                                </template>

                                {{-- Vaccine options --}}
                                <template x-if="selectedService === 'vaccine'">
                                    <template x-for="opt in serviceOptions.vaccine" :key="opt">
                                        <option :value="opt" x-text="opt"></option>
                                    </template>
                                </template>
                            </select>

                            <div x-show="specificService" class="flex items-center gap-2 mt-3">
                                <svg class="flex-shrink-0 w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700">
                                    Selected: <span class="font-semibold" x-text="specificService"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Medicine: show a note that no sub-selection needed --}}
                    <div x-show="selectedService === 'medicine'" x-transition class="mt-6">
                        <div class="flex items-center gap-3 p-4 border-2 border-blue-200 bg-blue-50 rounded-xl">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-blue-200 rounded-lg">
                                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">You'll pick your medicine in the next steps.</span>
                                First select your preferred date &amp; time slot, then fill out the medicine request form.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        {{-- Medicine: only needs selectedService (no specificService required) --}}
                        <button @click="nextStep()"
                                :disabled="!selectedService || (selectedService !== 'medicine' && !specificService)"
                                class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700">
                            Continue
                        </button>
                    </div>
                </div>

                {{-- ══════════════ STEP 2: Date + Time Slots ══════════════ --}}
                <div x-show="step === 2" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Select Date and Time</h2>

                    {{-- Service summary bar --}}
                    <div class="flex items-center gap-3 p-3 mb-5 text-sm border border-gray-200 rounded-lg bg-gray-50">
                        <span class="text-gray-500">Service:</span>
                        <span class="font-semibold text-gray-800"
                              x-text="selectedService === 'checkup' ? 'Check Up' : (selectedService === 'vaccine' ? 'Vaccine' : 'Request Medicine')"></span>
                        <template x-if="selectedService !== 'medicine' && specificService">
                            <span class="text-gray-400">→</span>
                        </template>
                        <template x-if="selectedService !== 'medicine' && specificService">
                            <span class="font-semibold text-blue-700" x-text="specificService"></span>
                        </template>
                        <template x-if="selectedService === 'medicine'">
                            <span class="px-2 py-0.5 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">Medicine form in next step</span>
                        </template>
                    </div>

                    <div class="flex flex-wrap items-end gap-3 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Select Date</label>
                            <input type="date" x-model="selectedDate" @change="fetchSlots()" :min="minDate()"
                                   class="block px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <button x-show="slots.length > 0 && !loadingSlots" @click="fetchSlots()" type="button"
                                class="flex items-center gap-2 px-4 py-3 text-sm font-semibold text-blue-700 border border-blue-300 rounded-lg bg-blue-50 hover:bg-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>

                    <div x-show="selectedDate" class="flex flex-wrap items-center gap-5 mb-5 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded"></div>
                            <span class="text-gray-600">Available</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-blue-600 border-2 border-blue-600 rounded"></div>
                            <span class="text-gray-600">Selected</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 border-2 border-red-300 rounded bg-red-50"></div>
                            <span class="text-gray-600">Full</span>
                        </div>
                    </div>

                    <div x-show="loadingSlots" class="flex items-center justify-center py-10">
                        <div class="w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
                        <span class="ml-3 text-sm text-gray-600">Checking availability…</span>
                    </div>

                    <div x-show="!selectedDate && !loadingSlots" class="py-10 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Select a date to see available time slots.</p>
                    </div>

                    <div x-show="slots.length > 0 && !loadingSlots" class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                        <template x-for="slot in slots" :key="slot.hour">
                            <button @click="selectSlot(slot)" type="button" :disabled="slot.is_full"
                                    class="relative flex flex-col items-center justify-center p-5 transition-all duration-200 border-2 rounded-xl focus:outline-none"
                                    :class="{
                                        'border-blue-600 bg-blue-600 text-white shadow-lg scale-105': selectedTime === slot.time_value,
                                        'border-green-400 bg-green-50 text-green-900 hover:border-green-600 hover:bg-green-100 cursor-pointer': !slot.is_full && selectedTime !== slot.time_value,
                                        'border-red-200 bg-red-50 text-red-400 cursor-not-allowed': slot.is_full
                                    }">
                                <span class="text-lg font-bold leading-tight" x-text="slot.label"></span>
                                <div class="w-full mt-3 mb-2 bg-gray-200 rounded-full h-1.5 overflow-hidden"
                                     :class="{'bg-blue-300': selectedTime === slot.time_value, 'bg-red-100': slot.is_full}">
                                    <div class="h-1.5 rounded-full transition-all duration-300"
                                         :class="{'bg-white': selectedTime === slot.time_value, 'bg-green-500': !slot.is_full && selectedTime !== slot.time_value, 'bg-red-400': slot.is_full}"
                                         :style="`width: ${(slot.booked / 10) * 100}%`">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between w-full text-xs font-medium">
                                    <span :class="{'text-blue-100': selectedTime === slot.time_value, 'text-gray-500': !slot.is_full && selectedTime !== slot.time_value, 'text-red-400': slot.is_full}">
                                        <span x-text="slot.booked"></span> taken
                                    </span>
                                    <span :class="{'text-white font-bold': selectedTime === slot.time_value && !slot.is_full, 'text-green-700 font-bold': !slot.is_full && selectedTime !== slot.time_value, 'text-red-400': slot.is_full}">
                                        <span x-text="slot.available"></span> left
                                    </span>
                                </div>
                                <span x-show="slot.is_full" class="mt-2 px-2 py-0.5 text-xs font-bold text-red-600 bg-red-100 rounded-full">FULL</span>
                                <span x-show="selectedTime === slot.time_value && !slot.is_full" class="absolute top-2 right-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button @click="previousStep()"
                                class="px-6 py-3 font-semibold text-gray-700 transition-colors border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                            Back
                        </button>
                        <button @click="nextStep()" :disabled="!selectedDate || !selectedTime"
                                class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700">
                            Continue
                        </button>
                    </div>
                </div>

                {{-- ══════════════ STEP 3A: Patient Details (checkup & vaccine) ══════════════ --}}
                <div x-show="step === 3 && selectedService !== 'medicine'" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Patient Details</h2>

                    <form @submit.prevent="submitAppointment()">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">First Name <span class="text-red-600">*</span></label>
                                    <input type="text" x-model="formData.firstName" required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Middle Initial</label>
                                    <input type="text" x-model="formData.middleInitial" maxlength="1"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Last Name <span class="text-red-600">*</span></label>
                                    <input type="text" x-model="formData.lastName" required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Birthdate <span class="text-red-600">*</span></label>
                                    <input type="date" x-model="formData.birthdate" @change="calcAge()" required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Age <span class="text-red-600">*</span></label>
                                    <div class="relative">
                                        <input type="number" x-model="formData.age" required min="0" max="120" readonly
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg cursor-not-allowed bg-gray-50 focus:ring-0"
                                               placeholder="Auto-filled">
                                        <span x-show="isMinor()" class="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">Minor</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Gender <span class="text-red-600">*</span></label>
                                    <select x-model="formData.gender" required
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Minor notice --}}
                            <div x-show="isMinor()" x-transition class="flex items-start gap-3 p-4 border rounded-lg bg-amber-50 border-amber-300">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-amber-800">Patient is a Minor (under 18)</p>
                                    <p class="text-xs text-amber-700 mt-0.5">A parent or legal guardian must be present. Please complete the guardian information below.</p>
                                </div>
                            </div>

                            {{-- Guardian fields --}}
                            <div x-show="isMinor()" x-transition class="p-5 space-y-4 border-2 bg-amber-50 border-amber-200 rounded-xl">
                                <h3 class="text-sm font-bold tracking-wide uppercase text-amber-800">Parent / Guardian Information</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-semibold text-gray-700">Guardian Full Name <span class="text-red-600">*</span></label>
                                        <input type="text" x-model="formData.guardianName" :required="isMinor()"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-semibold text-gray-700">Relationship <span class="text-red-600">*</span></label>
                                        <select x-model="formData.guardianRelationship" :required="isMinor()"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400">
                                            <option value="">Select Relationship</option>
                                            <option value="mother">Mother</option>
                                            <option value="father">Father</option>
                                            <option value="guardian">Legal Guardian</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Guardian Contact Number <span class="text-red-600">*</span></label>
                                    <input type="tel" x-model="formData.guardianContact" :required="isMinor()"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400">
                                </div>
                                <div class="flex items-start gap-3 p-3 bg-white border rounded-lg border-amber-200">
                                    <input type="checkbox" x-model="formData.guardianConsent" :required="isMinor()"
                                           class="w-4 h-4 mt-1 rounded text-amber-500 border-amber-300 focus:ring-amber-400">
                                    <label class="text-sm text-gray-700">
                                        <span class="font-semibold text-amber-800">I, the parent/guardian, give my consent</span>
                                        to this appointment and confirm I will be present at the health center.
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Email <span class="text-red-600">*</span></label>
                                    <input type="email" x-model="formData.email" required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Phone Number <span class="text-red-600">*</span></label>
                                    <input type="tel" x-model="formData.phoneNumber" required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Purok No. <span class="text-red-600">*</span></label>
                                <select x-model="formData.purokNo" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select Purok No.</option>
                                    <option value="1">Purok 1</option>
                                    <option value="2">Purok 2</option>
                                    <option value="3">Purok 3</option>
                                    <option value="4">Purok 4</option>
                                    <option value="5">Purok 5</option>
                                    <option value="6">Purok 6</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Additional Notes (Optional)</label>
                                <textarea x-model="formData.notes" rows="3" maxlength="500"
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Any additional information…"></textarea>
                            </div>

                            {{-- Booking summary --}}
                            <div class="p-4 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                                <p class="mb-2 font-semibold">Booking Summary</p>
                                <div class="space-y-1">
                                    <p>Service: <span class="font-medium" x-text="selectedService === 'checkup' ? 'Check Up' : 'Vaccine'"></span></p>
                                    <p>Specific: <span class="font-medium text-blue-700" x-text="specificService"></span></p>
                                    <p>Date: <span class="font-medium" x-text="selectedDate"></span></p>
                                    <p>Time: <span class="font-medium" x-text="slots.find(s => s.time_value === selectedTime)?.label || selectedTime"></span></p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox" x-model="agreedToTerms" required
                                       class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-600">
                                    I agree with <a href="#" class="text-blue-600 hover:underline">terms, agreement and policy</a>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button @click="previousStep()" type="button"
                                    class="px-6 py-3 font-semibold text-gray-700 transition-colors border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                Back
                            </button>
                            <button type="submit" :disabled="!agreedToTerms"
                                    class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700">
                                Schedule Now
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ══════════════ STEP 3B: Medicine Request Form ══════════════ --}}
                <div x-show="step === 3 && selectedService === 'medicine'" x-transition>

                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Medicine Request</h2>
                            <p class="text-sm text-gray-500">Appointment: <span class="font-medium" x-text="selectedDate"></span> at <span class="font-medium" x-text="slots.find(s => s.time_value === selectedTime)?.label || selectedTime"></span></p>
                        </div>
                    </div>

                    {{-- Patient info auto-fill note --}}
                    <div class="flex items-center gap-2 p-3 mb-5 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                        <svg class="flex-shrink-0 w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Requesting as: <strong>{{ Auth::user()->name }}</strong> — your account info will be attached automatically.</span>
                    </div>

                    <form @submit.prevent="submitMedicineRequest()">
                        <div class="space-y-5">

                            {{-- Medicine Selection --}}
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Select Medicine <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select x-model="medicineForm.medication_name" required
                                            class="w-full py-3 pl-4 pr-10 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Select medicine --</option>
                                        @foreach($medicineList as $category => $items)
                                            <optgroup label="{{ $category }}">
                                                @foreach($items as $med)
                                                    <option value="{{ $med }}">{{ $med }}</option>
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

                            {{-- Dosage & Quantity --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                                        Dosage <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="medicineForm.dosage" required
                                            class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500">
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
                                    <select x-model="medicineForm.quantity_requested" required
                                            class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select qty</option>
                                        <option value="10">10 pcs</option>
                                        <option value="20">20 pcs</option>
                                        <option value="30">30 pcs</option>
                                        <option value="60">60 pcs</option>
                                        <option value="90">90 pcs (3-month)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Frequency & Duration --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Frequency</label>
                                    <select x-model="medicineForm.frequency"
                                            class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select frequency</option>
                                        <option>Once daily</option>
                                        <option>Twice daily</option>
                                        <option>Three times daily</option>
                                        <option>Every 6 hours</option>
                                        <option>Every 8 hours</option>
                                        <option>As needed</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Duration</label>
                                    <select x-model="medicineForm.duration_days"
                                            class="w-full px-4 py-3 text-sm bg-white border border-gray-300 appearance-none rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select duration</option>
                                        <option value="3">3 days</option>
                                        <option value="5">5 days</option>
                                        <option value="7">7 days</option>
                                        <option value="14">14 days</option>
                                        <option value="30">30 days</option>
                                        <option value="90">90 days</option>
                                        <option value="0">Ongoing / Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Purpose --}}
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Purpose / Condition <span class="font-normal text-gray-400">(optional)</span>
                                </label>
                                <textarea x-model="medicineForm.instructions" rows="2" maxlength="300"
                                          class="block w-full px-4 py-3 text-sm border border-gray-300 resize-none rounded-xl focus:ring-2 focus:ring-blue-500"
                                          placeholder="e.g. For hypertension maintenance, diabetes management..."></textarea>
                            </div>

                            {{-- Prescription Upload (optional) --}}
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
                                        <p class="mb-3 text-xs text-gray-500">Requests with a doctor's prescription are processed faster and with priority.</p>
                                        <input type="file" id="modalPrescriptionImage"
                                               accept="image/jpeg,image/png,image/jpg"
                                               @change="handleMedFile($event)"
                                               class="block w-full text-sm text-gray-500 cursor-pointer file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                        <p class="mt-1 text-xs text-gray-400">JPG or PNG, max 5MB</p>
                                        <div x-show="medPreviewUrl" class="relative inline-block mt-3">
                                            <img :src="medPreviewUrl" class="object-cover h-16 border border-gray-200 rounded-lg">
                                            <button type="button" @click="clearMedFile()"
                                                    class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600">✕</button>
                                            <p class="mt-1 text-xs font-medium text-green-600">✓ Prescription attached</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Booking summary --}}
                            <div class="p-4 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                                <p class="mb-1 font-semibold">Appointment Summary</p>
                                <p>Service: <span class="font-medium">Request Medicine</span></p>
                                <p>Date: <span class="font-medium" x-text="selectedDate"></span></p>
                                <p>Time: <span class="font-medium" x-text="slots.find(s => s.time_value === selectedTime)?.label || selectedTime"></span></p>
                            </div>

                            {{-- Terms --}}
                            <div class="flex items-start">
                                <input type="checkbox" x-model="agreedToTerms" required
                                       class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-600">
                                    I agree with <a href="#" class="text-blue-600 hover:underline">terms, agreement and policy</a>
                                </label>
                            </div>

                            {{-- Error area --}}
                            <div id="modalMedError" class="hidden p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-xl"></div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button @click="previousStep()" type="button"
                                    class="px-6 py-3 font-semibold text-gray-700 transition-colors border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                Back
                            </button>
                            <button type="submit" :disabled="!agreedToTerms"
                                    id="modalMedSubmitBtn"
                                    class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>

            </div>{{-- end modal content --}}
        </div>
    </div>
</div>

<script>
function appointmentModal() {
    return {
        open:            false,
        step:            1,
        selectedService: '',
        specificService: '',
        selectedDate:    '',
        selectedTime:    '',
        agreedToTerms:   false,
        slots:           [],
        loadingSlots:    false,
        _pollInterval:   null,
        lastUpdated:     null,
        justUpdated:     false,
        medPreviewUrl:   null,

        // Service sub-options (checkup + vaccine only)
        serviceOptions: {
            checkup: @json(\App\Models\Appointment::serviceOptions()['checkup']),
            vaccine: @json(\App\Models\Appointment::serviceOptions()['vaccine']),
        },

        // Standard patient form
        formData: {
            firstName:            '{{ Auth::user()->name ?? "" }}',
            middleInitial:        '',
            lastName:             '',
            birthdate:            '',
            age:                  '',
            gender:               '',
            email:                '{{ Auth::user()->email ?? "" }}',
            phoneNumber:          '',
            purokNo:              '',
            notes:                '',
            guardianName:         '',
            guardianRelationship: '',
            guardianContact:      '',
            guardianConsent:      false,
        },

        // Medicine request form (Step 3B)
        medicineForm: {
            medication_name:    '',
            dosage:             '',
            quantity_requested: '',
            frequency:          '',
            duration_days:      '',
            instructions:       '',
        },

        minDate() {
            const t = new Date();
            t.setDate(t.getDate() + 1);
            return t.toISOString().split('T')[0];
        },

        openModal()  { this.open = true; document.body.style.overflow = 'hidden'; },
        closeModal() {
            this._stopPolling();
            this.open = false;
            document.body.style.overflow = '';
            setTimeout(() => this.resetForm(), 300);
        },

        resetForm() {
            this._stopPolling();
            this.step            = 1;
            this.selectedService = '';
            this.specificService = '';
            this.selectedDate    = '';
            this.selectedTime    = '';
            this.agreedToTerms   = false;
            this.slots           = [];
            this.loadingSlots    = false;
            this.lastUpdated     = null;
            this.justUpdated     = false;
            this.medPreviewUrl   = null;
            this.formData = {
                firstName:            '{{ Auth::user()->name ?? "" }}',
                middleInitial:        '',
                lastName:             '',
                birthdate:            '',
                age:                  '',
                gender:               '',
                email:                '{{ Auth::user()->email ?? "" }}',
                phoneNumber:          '',
                purokNo:              '',
                notes:                '',
                guardianName:         '',
                guardianRelationship: '',
                guardianContact:      '',
                guardianConsent:      false,
            };
            this.medicineForm = {
                medication_name:    '',
                dosage:             '',
                quantity_requested: '',
                frequency:          '',
                duration_days:      '',
                instructions:       '',
            };
            const fi = document.getElementById('modalPrescriptionImage');
            if (fi) fi.value = '';
        },

        selectService(service) {
            this.selectedService = service;
            this.specificService = '';  // clear on switch
        },

        calcAge() {
            if (!this.formData.birthdate) return;
            const today = new Date();
            const dob   = new Date(this.formData.birthdate);
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            this.formData.age = age >= 0 ? age : '';
            if (!this.isMinor()) {
                this.formData.guardianName         = '';
                this.formData.guardianRelationship = '';
                this.formData.guardianContact      = '';
                this.formData.guardianConsent      = false;
            }
        },

        isMinor() {
            const age = parseInt(this.formData.age, 10);
            return !isNaN(age) && age < 18;
        },

        nextStep() {
            if (this.step < 3) {
                this.step++;
                if (this.step === 2) this._startPolling();
            }
        },

        previousStep() {
            if (this.step > 1) {
                this._stopPolling();
                this.step--;
            }
        },

        _startPolling() {
            this._stopPolling();
            this._pollInterval = setInterval(() => {
                if (this.selectedDate && this.step === 2) this._silentRefresh();
            }, 20000);
        },

        _stopPolling() {
            if (this._pollInterval) { clearInterval(this._pollInterval); this._pollInterval = null; }
        },

        async _silentRefresh() {
            if (!this.selectedDate) return;
            try {
                const res  = await fetch(`{{ route('appointments.slots') }}?date=${this.selectedDate}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (!res.ok) return;
                const data  = await res.json();
                const fresh = data.slots || [];
                this.slots = this.slots.map(e => {
                    const u = fresh.find(s => s.hour === e.hour);
                    return u ? { ...e, booked: u.booked, available: u.available, is_full: u.is_full } : e;
                });
                if (this.selectedTime) {
                    const chosen = this.slots.find(s => s.time_value === this.selectedTime);
                    if (chosen && chosen.is_full) {
                        this.selectedTime = '';
                        alert('⚠️ The slot you selected just became fully booked. Please choose another time.');
                    }
                }
                this.lastUpdated = new Date().toLocaleTimeString();
                this.justUpdated = true;
                setTimeout(() => { this.justUpdated = false; }, 2000);
            } catch (e) { /* silent */ }
        },

        async fetchSlots() {
            if (!this.selectedDate) return;
            this.loadingSlots = true;
            this.selectedTime = '';
            this.slots        = [];
            try {
                const res = await fetch(`{{ route('appointments.slots') }}?date=${this.selectedDate}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (!res.ok) throw new Error('Server error');
                const data = await res.json();
                this.slots = data.slots || [];
                this.lastUpdated = new Date().toLocaleTimeString();
            } catch (e) {
                console.error('Failed to fetch slots:', e);
                this.slots = [];
            } finally {
                this.loadingSlots = false;
            }
        },

        selectSlot(slot) {
            if (!slot.is_full) this.selectedTime = slot.time_value;
        },

        // ── Medicine file handling ──────────────────────────────────────────
        handleMedFile(event) {
            const file = event.target.files[0];
            if (!file) { this.medPreviewUrl = null; return; }
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be under 5MB.');
                event.target.value = '';
                this.medPreviewUrl = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => { this.medPreviewUrl = e.target.result; };
            reader.readAsDataURL(file);
        },

        clearMedFile() {
            this.medPreviewUrl = null;
            const fi = document.getElementById('modalPrescriptionImage');
            if (fi) fi.value = '';
        },

        // ── Submit: Standard appointment (checkup / vaccine) ───────────────
        async submitAppointment() {
            if (!this.selectedService || !this.specificService || !this.selectedDate || !this.selectedTime) {
                alert('Please complete all appointment details including the specific service.');
                return;
            }
            if (!this.formData.firstName || !this.formData.lastName || !this.formData.birthdate ||
                !this.formData.age || !this.formData.gender || !this.formData.email ||
                !this.formData.phoneNumber || !this.formData.purokNo) {
                alert('Please complete all required patient information.');
                return;
            }
            if (!this.agreedToTerms) { alert('Please agree to the terms and conditions.'); return; }
            if (this.isMinor()) {
                if (!this.formData.guardianName || !this.formData.guardianRelationship || !this.formData.guardianContact) {
                    alert('Patient is a minor. Please complete all guardian information.');
                    return;
                }
                if (!this.formData.guardianConsent) {
                    alert('Patient is a minor. The parent or guardian must tick the consent checkbox.');
                    return;
                }
            }

            const payload = {
                service_type:          this.selectedService,
                specific_service:      this.specificService,
                appointment_date:      this.selectedDate,
                appointment_time:      this.selectedTime,
                first_name:            this.formData.firstName,
                middle_initial:        this.formData.middleInitial,
                last_name:             this.formData.lastName,
                birthdate:             this.formData.birthdate,
                age:                   this.formData.age,
                gender:                this.formData.gender,
                email:                 this.formData.email,
                phone_number:          this.formData.phoneNumber,
                purok_no:              this.formData.purokNo,
                notes:                 this.formData.notes,
                guardian_name:         this.formData.guardianName         || null,
                guardian_relationship: this.formData.guardianRelationship || null,
                guardian_contact:      this.formData.guardianContact       || null,
                guardian_consent:      this.formData.guardianConsent       || false,
                _token:                '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("appointments.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept':       'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    window.location.href = '{{ route("appointments.index") }}';
                } else {
                    alert(data.message || 'Error booking appointment. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('There was an error booking your appointment. Please try again.');
            }
        },

        // ── Submit: Medicine Request (Step 3B) ──────────────────────────────
        async submitMedicineRequest() {
            if (!this.medicineForm.medication_name || !this.medicineForm.dosage || !this.medicineForm.quantity_requested) {
                alert('Please select a medicine, dosage, and quantity.');
                return;
            }
            if (!this.agreedToTerms) { alert('Please agree to the terms and conditions.'); return; }

            const btn    = document.getElementById('modalMedSubmitBtn');
            const errDiv = document.getElementById('modalMedError');
            btn.disabled = true;
            btn.textContent = 'Submitting…';
            if (errDiv) errDiv.classList.add('hidden');

            // 1. Book the appointment slot first
            const appointmentPayload = {
                service_type:     'medicine',
                specific_service: this.medicineForm.medication_name,
                appointment_date: this.selectedDate,
                appointment_time: this.selectedTime,
                // Use logged-in user data
                first_name:   '{{ Auth::user()->name ?? "" }}'.split(' ')[0] || '{{ Auth::user()->name ?? "" }}',
                middle_initial: '',
                last_name:    '{{ Auth::user()->name ?? "" }}'.split(' ').slice(1).join(' ') || '.',
                birthdate:    '{{ Auth::user()->birthdate ?? now()->subYears(25)->format("Y-m-d") }}',
                age:          {{ Auth::user()->age ?? 25 }},
                gender:       '{{ Auth::user()->gender ?? "other" }}',
                email:        '{{ Auth::user()->email ?? "" }}',
                phone_number: '{{ Auth::user()->phone_number ?? "N/A" }}',
                purok_no:     '{{ Auth::user()->purok_no ?? "1" }}',
                notes:        this.medicineForm.instructions || '',
                guardian_name: null,
                guardian_relationship: null,
                guardian_contact: null,
                guardian_consent: false,
                _token: '{{ csrf_token() }}'
            };

            try {
                const aptRes  = await fetch('{{ route("appointments.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept':       'application/json'
                    },
                    body: JSON.stringify(appointmentPayload)
                });
                const aptData = await aptRes.json();

                if (!aptRes.ok || !aptData.success) {
                    throw new Error(aptData.message || 'Failed to book appointment slot.');
                }

                // 2. Submit the medicine request (with optional prescription image)
                const formData = new FormData();
                formData.append('medication_name',    this.medicineForm.medication_name);
                formData.append('dosage',             this.medicineForm.dosage);
                formData.append('quantity_requested', this.medicineForm.quantity_requested);
                if (this.medicineForm.frequency)    formData.append('frequency',    this.medicineForm.frequency);
                if (this.medicineForm.duration_days) formData.append('duration_days', this.medicineForm.duration_days);
                if (this.medicineForm.instructions)  formData.append('instructions',  this.medicineForm.instructions);
                formData.append('_token', '{{ csrf_token() }}');

                const fileInput = document.getElementById('modalPrescriptionImage');
                if (fileInput && fileInput.files[0]) {
                    formData.append('prescription_image', fileInput.files[0]);
                }

                const rxRes  = await fetch('{{ route("prescriptions.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const rxData = await rxRes.json();

                if (rxRes.ok && rxData.success) {
                    window.location.href = '{{ route("appointments.index") }}';
                } else {
                    throw new Error(rxData.message || 'Medicine request submission failed.');
                }

            } catch (err) {
                console.error('Medicine request error:', err);
                if (errDiv) {
                    errDiv.textContent = err.message || 'An unexpected error occurred. Please try again.';
                    errDiv.classList.remove('hidden');
                } else {
                    alert(err.message || 'An unexpected error occurred. Please try again.');
                }
                btn.disabled = false;
                btn.textContent = 'Submit Request';
            }
        }
    }
}
</script>