<!-- Step 1: Service Selection -->
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

<!-- Step 2: Date and Time Selection -->
<div x-show="step === 2" x-transition>
    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Select Date and Time</h2>
    
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Calendar -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">Select Date</label>
            <input type="date" x-model="selectedDate" 
                   :min="new Date().toISOString().split('T')[0]"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Time Slots -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">Select Time Slot</label>
            <div class="grid grid-cols-2 gap-3 overflow-y-auto max-h-64">
                <template x-for="slot in timeSlots" :key="slot.value">
                    <button @click="selectedTime = slot.value" type="button"
                            class="px-4 py-2 text-sm font-medium transition-all border-2 rounded-lg"
                            :class="selectedTime === slot.value ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 hover:border-blue-300'">
                        <span x-text="slot.label"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div class="flex justify-between mt-6">
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

<!-- Step 3: Patient Details Form -->
<div x-show="step === 3" x-transition>
    <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Patient Details</h2>
    
    <form @submit.prevent="submitAppointment()">
        <div class="space-y-4">
            <!-- Row 1: First Name, Middle Initial, Last Name -->
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

            <!-- Row 2: Birthdate, Age, Gender -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Birthdate <span class="text-red-600">*</span></label>
                    <input type="date" x-model="formData.birthdate" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Age <span class="text-red-600">*</span></label>
                    <input type="number" x-model="formData.age" required min="0" max="120"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
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

            <!-- Row 3: Email, Phone Number -->
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

            <!-- Row 4: Purok No. -->
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

            <!-- Additional Notes -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">Additional Notes (Optional)</label>
                <textarea x-model="formData.notes" rows="3" maxlength="500"
                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Please provide any additional information..."></textarea>
            </div>

            <!-- Terms and Conditions -->
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