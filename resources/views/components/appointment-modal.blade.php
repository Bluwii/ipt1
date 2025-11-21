<div x-data="{
    showAppointmentModal: false,
    selectedService: '',
    appointmentDate: '',
    appointmentTime: '',
    notes: '',
    loading: false,
    
    init() {
        // Listen for the open modal event
        this.$watch('showAppointmentModal', (value) => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });

        // Listen for custom event to open modal
        this.$el.addEventListener('open-booking-modal', () => {
            this.openModal();
        });
    },
    
    openModal() {
        this.showAppointmentModal = true;
    },
    
    closeModal() {
        this.showAppointmentModal = false;
        this.selectedService = '';
        this.appointmentDate = '';
        this.appointmentTime = '';
        this.notes = '';
    },
    
    async submitAppointment() {
        this.loading = true;
        
        try {
            const formData = new FormData();
            formData.append('service_type', this.selectedService);
            formData.append('appointment_date', this.appointmentDate);
            formData.append('appointment_time', this.appointmentTime);
            formData.append('notes', this.notes);
            formData.append('_token', '{{ csrf_token() }}');
            
            const response = await fetch('{{ route('appointments.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Success
                this.closeModal();
                // Show success message
                alert('Appointment booked successfully!');
                window.location.reload();
            } else {
                // Show error
                alert(result.message || 'Error booking appointment');
            }
        } catch (error) {
            alert('Network error. Please try again.');
        } finally {
            this.loading = false;
        }
    }
}" 
x-init="
    // Set minimum date to tomorrow
    $watch('appointmentDate', (value) => {
        if (value) {
            const today = new Date().toISOString().split('T')[0];
            if (value <= today) {
                this.appointmentDate = '';
            }
        }
    })
">
    <!-- Backdrop and Modal -->
    <div x-show="showAppointmentModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm">
        
        <!-- Modal Container -->
        <div x-show="showAppointmentModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="closeModal"
             class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl">
            
            <!-- Close Button -->
            <button @click="closeModal" 
                    class="absolute top-4 right-4 z-10 p-2 text-gray-400 transition-colors rounded-full hover:text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Modal Content -->
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 uppercase">Book New Appointment</h2>
                    <p class="mt-2 text-gray-600">Choose a service and schedule your visit</p>
                </div>

                <!-- Service Selection -->
                <div class="mb-8">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Select Service Type</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Check Up Card -->
                        <label class="cursor-pointer">
                            <input type="radio" name="service_type" x-model="selectedService" value="checkup" class="sr-only peer">
                            <div class="p-4 text-center transition-all border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300 hover:shadow-lg">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="p-3 bg-blue-100 rounded-lg">
                                        <img src="{{ asset('image/bed.png') }}" alt="Check Up" class="w-12 h-12">
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900">Check Up</h4>
                            </div>
                        </label>

                        <!-- Vaccine Card -->
                        <label class="cursor-pointer">
                            <input type="radio" name="service_type" x-model="selectedService" value="vaccine" class="sr-only peer">
                            <div class="p-4 text-center transition-all border-2 border-gray-200 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300 hover:shadow-lg">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="p-3 bg-red-100 rounded-lg">
                                        <img src="{{ asset('image/injection.png') }}" alt="Vaccine" class="w-12 h-12">
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900">Vaccine</h4>
                            </div>
                        </label>

                        <!-- Medicine Card -->
                        <label class="cursor-pointer">
                            <input type="radio" name="service_type" x-model="selectedService" value="medicine" class="sr-only peer">
                            <div class="p-4 text-center transition-all border-2 border-gray-200 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-purple-300 hover:shadow-lg">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="p-3 bg-purple-100 rounded-lg">
                                        <img src="{{ asset('image/meds.png') }}" alt="Medicine" class="w-12 h-12">
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900">Request Medicine</h4>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Appointment Details (Shows when service is selected) -->
                <div x-show="selectedService" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="p-6 border-t border-gray-200">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Appointment Details</h3>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <!-- Date -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Preferred Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       x-model="appointmentDate"
                                       :min="new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0]"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Time -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Preferred Time <span class="text-red-500">*</span>
                                </label>
                                <select x-model="appointmentTime" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select time</option>
                                    <option value="08:00">8:00 AM - 9:00 AM</option>
                                    <option value="09:00">9:00 AM - 10:00 AM</option>
                                    <option value="10:00">10:00 AM - 11:00 AM</option>
                                    <option value="11:00">11:00 AM - 12:00 PM</option>
                                    <option value="13:00">1:00 PM - 2:00 PM</option>
                                    <option value="14:00">2:00 PM - 3:00 PM</option>
                                    <option value="15:00">3:00 PM - 4:00 PM</option>
                                    <option value="16:00">4:00 PM - 5:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Additional Notes (Optional)
                            </label>
                            <textarea x-model="notes" 
                                      rows="3" 
                                      placeholder="Any additional information or special requests..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-6">
                            <button @click="submitAppointment" 
                                    :disabled="loading || !appointmentDate || !appointmentTime"
                                    :class="{'opacity-50 cursor-not-allowed': loading || !appointmentDate || !appointmentTime}"
                                    class="flex-1 px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 disabled:hover:bg-blue-600">
                                <span x-show="!loading">Book Appointment</span>
                                <span x-show="loading" class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m16.364-6.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M4.636 19.364l2.828-2.828"></path>
                                    </svg>
                                    Booking...
                                </span>
                            </button>
                            <button @click="closeModal" 
                                    class="flex-1 px-6 py-3 font-semibold text-gray-700 transition-colors border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>