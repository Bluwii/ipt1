<!-- resources/views/components/appointment-modal.blade.php -->
<div x-data="appointmentModal()" x-show="open" x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @open-appointment-modal.window="openModal()"
     @keydown.escape.window="closeModal()">

    <!-- Backdrop -->
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

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative inline-block w-full max-w-5xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header with Close Button -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Schedule Appointment</h2>
                <button @click="closeModal()" class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Progress Indicator -->
            <div class="px-6 pt-4 pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center" :class="step >= 1 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">1</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Service</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center" :class="step >= 2 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">2</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Schedule</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center" :class="step >= 3 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full"
                             :class="step >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">3</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Details</span>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(100vh-250px)]">

                <!-- ══════════════ STEP 1: Service ══════════════ -->
                <div x-show="step === 1" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Choose a Service</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Check Up -->
                        <button @click="selectService('checkup')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'checkup' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-blue-100 rounded-xl">
                                    <img src="{{ asset('image/bed.png') }}" alt="Check Up" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Check Up</h3>
                            <div x-show="selectedService === 'checkup'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-blue-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        <!-- Vaccine -->
                        <button @click="selectService('vaccine')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'vaccine' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-red-100 rounded-xl">
                                    <img src="{{ asset('image/injection.png') }}" alt="Vaccine" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Vaccine</h3>
                            <div x-show="selectedService === 'vaccine'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-red-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        <!-- Request Medicine -->
                        <button @click="selectService('medicine')" type="button"
                                class="relative p-6 text-center transition-all border-2 rounded-xl"
                                :class="selectedService === 'medicine' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300'">
                            <div class="flex items-center justify-center mb-4">
                                <div class="p-4 bg-purple-100 rounded-xl">
                                    <img src="{{ asset('image/meds.png') }}" alt="Medicine" class="w-16 h-16">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Request Medicine</h3>
                            <div x-show="selectedService === 'medicine'" class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-6 h-6 text-white bg-purple-600 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button @click="nextStep()" :disabled="!selectedService"
                                class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700">
                            Continue
                        </button>
                    </div>
                </div>

                <!-- ══════════════ STEP 2: Date + Time Slots ══════════════ -->
                <div x-show="step === 2" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Select Date and Time</h2>

                    <!-- Date Picker + Refresh row -->
                    <div class="flex flex-wrap items-end gap-3 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Select Date</label>
                            <input type="date"
                                   x-model="selectedDate"
                                   @change="fetchSlots()"
                                   :min="minDate()"
                                   class="block px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <!-- Refresh button — only shown when slots are loaded -->
                        <button x-show="slots.length > 0 && !loadingSlots"
                                @click="fetchSlots()"
                                type="button"
                                class="flex items-center gap-2 px-4 py-3 text-sm font-semibold text-blue-700 transition-colors border border-blue-300 rounded-lg bg-blue-50 hover:bg-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>

                    <!-- Legend -->
                    <div x-show="selectedDate" class="flex flex-wrap items-center gap-5 mb-5 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded"></div>
                            <span class="text-gray-600">Available slots</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-blue-600 border-2 border-blue-600 rounded"></div>
                            <span class="text-gray-600">Selected</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 border-2 border-red-300 rounded bg-red-50"></div>
                            <span class="text-gray-600">Full — no slots left</span>
                        </div>
                    </div>

                    <!-- Loading spinner -->
                    <div x-show="loadingSlots" class="flex items-center justify-center py-10">
                        <div class="w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
                        <span class="ml-3 text-sm text-gray-600">Checking availability…</span>
                    </div>

                    <!-- Prompt when no date selected -->
                    <div x-show="!selectedDate && !loadingSlots" class="py-10 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm">Select a date above to see available time slots.</p>
                    </div>

                    <!-- ── Time Slot Cards ── -->
                    <div x-show="slots.length > 0 && !loadingSlots"
                         class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                        <template x-for="slot in slots" :key="slot.hour">
                            <button
                                @click="selectSlot(slot)"
                                type="button"
                                :disabled="slot.is_full"
                                class="relative flex flex-col items-center justify-center p-5 transition-all duration-200 border-2 rounded-xl focus:outline-none"
                                :class="{
                                    'border-blue-600 bg-blue-600 text-white shadow-lg scale-105':
                                        selectedTime === slot.time_value,
                                    'border-green-400 bg-green-50 text-green-900 hover:border-green-600 hover:bg-green-100 cursor-pointer':
                                        !slot.is_full && selectedTime !== slot.time_value,
                                    'border-red-200 bg-red-50 text-red-400 cursor-not-allowed':
                                        slot.is_full
                                }">

                                <!-- Hour label -->
                                <span class="text-lg font-bold leading-tight" x-text="slot.label"></span>

                                <!-- Visual progress bar -->
                                <div class="w-full mt-3 mb-2 bg-gray-200 rounded-full h-1.5 overflow-hidden"
                                     :class="{'bg-blue-300': selectedTime === slot.time_value, 'bg-red-100': slot.is_full}">
                                    <div class="h-1.5 rounded-full transition-all duration-300"
                                         :class="{
                                             'bg-white':       selectedTime === slot.time_value,
                                             'bg-green-500':   !slot.is_full && selectedTime !== slot.time_value,
                                             'bg-red-400':     slot.is_full
                                         }"
                                         :style="`width: ${(slot.booked / 10) * 100}%`">
                                    </div>
                                </div>

                                <!-- Booked / available counts -->
                                <div class="flex items-center justify-between w-full text-xs font-medium">
                                    <span :class="{
                                              'text-blue-100':  selectedTime === slot.time_value,
                                              'text-gray-500':  !slot.is_full && selectedTime !== slot.time_value,
                                              'text-red-400':   slot.is_full
                                          }">
                                        <span x-text="slot.booked"></span> taken
                                    </span>
                                    <span :class="{
                                              'text-white font-bold':   selectedTime === slot.time_value && !slot.is_full,
                                              'text-green-700 font-bold': !slot.is_full && selectedTime !== slot.time_value,
                                              'text-red-400':            slot.is_full
                                          }">
                                        <span x-text="slot.available"></span> left
                                    </span>
                                </div>

                                <!-- FULL badge -->
                                <span x-show="slot.is_full"
                                      class="mt-2 px-2 py-0.5 text-xs font-bold text-red-600 bg-red-100 rounded-full">
                                    FULL
                                </span>

                                <!-- Selected checkmark -->
                                <span x-show="selectedTime === slot.time_value && !slot.is_full"
                                      class="absolute top-2 right-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>

                    <!-- Note about live counts -->
                    <div x-show="slots.length > 0 && !loadingSlots"
                         class="flex items-center justify-between mt-4 text-xs text-gray-400">
                        <span>
                            Slot counts are live.
                            <span x-show="lastUpdated">Last updated: <span x-text="lastUpdated"></span></span>
                        </span>
                        <!-- Flash indicator when silent refresh fires -->
                        <span x-show="justUpdated"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 scale-90"
                              x-transition:enter-end="opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-500"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0"
                              class="flex items-center gap-1 px-2 py-0.5 text-green-700 bg-green-100 rounded-full font-semibold">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Updated
                        </span>
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

                <!-- ══════════════ STEP 3: Patient Details ══════════════ -->
                <div x-show="step === 3" x-transition>
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Patient Details</h2>

                    <form @submit.prevent="submitAppointment()">
                        <div class="space-y-4">
                            <!-- Row 1: Name -->
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

                            <!-- Row 2: Birthdate / Age / Gender -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Birthdate <span class="text-red-600">*</span></label>
                                    <input type="date" x-model="formData.birthdate"
                                           @change="calcAge()"
                                           required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">Age <span class="text-red-600">*</span></label>
                                    <div class="relative">
                                        <input type="number" x-model="formData.age" required min="0" max="120" readonly
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg cursor-not-allowed bg-gray-50 focus:ring-0"
                                               placeholder="Auto-filled from birthdate">
                                        <!-- Minor badge shown inside the age field -->
                                        <span x-show="isMinor()"
                                              class="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">
                                            Minor
                                        </span>
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

                            <!-- ── Minor Notice Banner ── -->
                            <div x-show="isMinor()"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="flex items-start gap-3 p-4 border rounded-lg bg-amber-50 border-amber-300">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-amber-800">Patient is a Minor (under 18)</p>
                                    <p class="text-xs text-amber-700 mt-0.5">A parent or legal guardian must be present at the appointment. Please complete the guardian information below.</p>
                                </div>
                            </div>

                            <!-- ── Guardian / Parent Information (only for minors) ── -->
                            <div x-show="isMinor()"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 class="p-5 space-y-4 border-2 bg-amber-50 border-amber-200 rounded-xl">

                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                    </svg>
                                    <h3 class="text-sm font-bold tracking-wide uppercase text-amber-800">Parent / Guardian Information</h3>
                                </div>

                                <!-- Guardian name + relationship -->
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                                            Guardian Full Name <span class="text-red-600">*</span>
                                        </label>
                                        <input type="text" x-model="formData.guardianName"
                                               :required="isMinor()"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400"
                                               placeholder="e.g. Maria Santos">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-semibold text-gray-700">
                                            Relationship <span class="text-red-600">*</span>
                                        </label>
                                        <select x-model="formData.guardianRelationship"
                                                :required="isMinor()"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400">
                                            <option value="">Select Relationship</option>
                                            <option value="mother">Mother</option>
                                            <option value="father">Father</option>
                                            <option value="guardian">Legal Guardian</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Guardian contact -->
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                                        Guardian Contact Number <span class="text-red-600">*</span>
                                    </label>
                                    <input type="tel" x-model="formData.guardianContact"
                                           :required="isMinor()"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-amber-400 focus:ring-amber-400"
                                           placeholder="e.g. 09XX-XXX-XXXX">
                                </div>

                                <!-- Guardian consent checkbox -->
                                <div class="flex items-start gap-3 p-3 bg-white border rounded-lg border-amber-200">
                                    <input type="checkbox" x-model="formData.guardianConsent"
                                           :required="isMinor()"
                                           class="w-4 h-4 mt-1 rounded text-amber-500 border-amber-300 focus:ring-amber-400">
                                    <label class="text-sm text-gray-700">
                                        <span class="font-semibold text-amber-800">I, the parent/guardian, give my consent</span>
                                        to this appointment and confirm that I will be personally present at the health center on the scheduled date.
                                    </label>
                                </div>
                            </div>

                            <!-- Row 3: Email / Phone -->
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

                            <!-- Row 4: Purok -->
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

                            <!-- Notes -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Additional Notes (Optional)</label>
                                <textarea x-model="formData.notes" rows="3" maxlength="500"
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Please provide any additional information…"></textarea>
                            </div>

                            <!-- Appointment summary -->
                            <div class="p-4 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                                <p class="mb-1 font-semibold">Booking Summary</p>
                                <p>Service: <span class="font-medium" x-text="selectedService === 'checkup' ? 'Check Up' : (selectedService === 'vaccine' ? 'Vaccine' : 'Request Medicine')"></span></p>
                                <p>Date: <span class="font-medium" x-text="selectedDate"></span></p>
                                <p>Time: <span class="font-medium" x-text="slots.find(s => s.time_value === selectedTime)?.label || selectedTime"></span></p>
                            </div>

                            <!-- Terms -->
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

            </div><!-- end modal content -->
        </div>
    </div>
</div>

<script>
function appointmentModal() {
    return {
        open:            false,
        step:            1,
        selectedService: '',
        selectedDate:    '',
        selectedTime:    '',
        agreedToTerms:   false,
        slots:           [],
        loadingSlots:    false,
        _pollInterval:   null,
        lastUpdated:     null,
        justUpdated:     false,

        formData: {
            firstName:              '{{ Auth::user()->name ?? "" }}',
            middleInitial:          '',
            lastName:               '',
            birthdate:              '',
            age:                    '',
            gender:                 '',
            email:                  '{{ Auth::user()->email ?? "" }}',
            phoneNumber:            '',
            purokNo:                '',
            notes:                  '',
            // Guardian fields (only used when patient is a minor)
            guardianName:           '',
            guardianRelationship:   '',
            guardianContact:        '',
            guardianConsent:        false,
        },

        minDate() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            return tomorrow.toISOString().split('T')[0];
        },

        openModal() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

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
            this.selectedDate    = '';
            this.selectedTime    = '';
            this.agreedToTerms   = false;
            this.slots           = [];
            this.loadingSlots    = false;
            this.lastUpdated     = null;
            this.justUpdated     = false;
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
        },

        // Select a service in Step 1
        selectService(service) { this.selectedService = service; },

        // Auto-calculate age from birthdate
        calcAge() {
            if (!this.formData.birthdate) return;
            const today = new Date();
            const dob   = new Date(this.formData.birthdate);
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            this.formData.age = age >= 0 ? age : '';

            // Clear guardian fields if patient is no longer a minor
            if (!this.isMinor()) {
                this.formData.guardianName         = '';
                this.formData.guardianRelationship = '';
                this.formData.guardianContact      = '';
                this.formData.guardianConsent      = false;
            }
        },

        // Returns true if the currently-entered age is under 18
        isMinor() {
            const age = parseInt(this.formData.age, 10);
            return !isNaN(age) && age < 18;
        },

        nextStep() {
            if (this.step < 3) {
                this.step++;
                // Start polling when entering Step 2
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
            this._stopPolling(); // clear any existing timer
            // Poll every 20 seconds silently
            this._pollInterval = setInterval(() => {
                if (this.selectedDate && this.step === 2) {
                    this._silentRefresh();
                }
            }, 20000);
        },

        _stopPolling() {
            if (this._pollInterval) {
                clearInterval(this._pollInterval);
                this._pollInterval = null;
            }
        },

        // Silent refresh — updates counts without resetting selected slot or showing spinner
        async _silentRefresh() {
            if (!this.selectedDate) return;
            try {
                const res = await fetch(
                    `{{ route('appointments.slots') }}?date=${this.selectedDate}`,
                    { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }
                );
                if (!res.ok) return;
                const data = await res.json();
                const fresh = data.slots || [];

                // Merge counts into existing slots so UI updates in place
                this.slots = this.slots.map(existing => {
                    const updated = fresh.find(s => s.hour === existing.hour);
                    if (!updated) return existing;
                    return { ...existing, booked: updated.booked, available: updated.available, is_full: updated.is_full };
                });

                // If the selected slot just became full, deselect it and warn
                if (this.selectedTime) {
                    const chosen = this.slots.find(s => s.time_value === this.selectedTime);
                    if (chosen && chosen.is_full) {
                        this.selectedTime = '';
                        alert('⚠️ The slot you selected just became fully booked. Please choose another time.');
                    }
                }

                // Flash the "just updated" indicator for 2 s
                this.lastUpdated = new Date().toLocaleTimeString();
                this.justUpdated = true;
                setTimeout(() => { this.justUpdated = false; }, 2000);

            } catch (e) { /* silent fail — network blip */ }
        },

        // Full fetch — used when date changes (shows spinner, resets selection)
        async fetchSlots() {
            if (!this.selectedDate) return;

            this.loadingSlots = true;
            this.selectedTime = '';
            this.slots        = [];

            try {
                const res = await fetch(
                    `{{ route('appointments.slots') }}?date=${this.selectedDate}`,
                    {
                        headers: {
                            'Accept':        'application/json',
                            'X-CSRF-TOKEN':  '{{ csrf_token() }}'
                        }
                    }
                );

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
            if (!slot.is_full) {
                this.selectedTime = slot.time_value;
            }
        },

        async submitAppointment() {
            if (!this.selectedService || !this.selectedDate || !this.selectedTime) {
                alert('Please complete all appointment details.');
                return;
            }
            if (!this.formData.firstName || !this.formData.lastName || !this.formData.birthdate ||
                !this.formData.age || !this.formData.gender || !this.formData.email ||
                !this.formData.phoneNumber || !this.formData.purokNo) {
                alert('Please complete all required patient information.');
                return;
            }
            if (!this.agreedToTerms) {
                alert('Please agree to the terms and conditions.');
                return;
            }

            // Minor-specific validation
            if (this.isMinor()) {
                if (!this.formData.guardianName || !this.formData.guardianRelationship || !this.formData.guardianContact) {
                    alert('Patient is a minor. Please complete all guardian/parent information.');
                    return;
                }
                if (!this.formData.guardianConsent) {
                    alert('Patient is a minor. The parent or guardian must tick the consent checkbox before continuing.');
                    return;
                }
            }

            const payload = {
                service_type:           this.selectedService,
                appointment_date:       this.selectedDate,
                appointment_time:       this.selectedTime,
                first_name:             this.formData.firstName,
                middle_initial:         this.formData.middleInitial,
                last_name:              this.formData.lastName,
                birthdate:              this.formData.birthdate,
                age:                    this.formData.age,
                gender:                 this.formData.gender,
                email:                  this.formData.email,
                phone_number:           this.formData.phoneNumber,
                purok_no:               this.formData.purokNo,
                notes:                  this.formData.notes,
                guardian_name:          this.formData.guardianName         || null,
                guardian_relationship:  this.formData.guardianRelationship || null,
                guardian_contact:       this.formData.guardianContact       || null,
                guardian_consent:       this.formData.guardianConsent       || false,
                _token:                 '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("appointments.store") }}', {
                    method:  'POST',
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
        }
    }
}
</script>