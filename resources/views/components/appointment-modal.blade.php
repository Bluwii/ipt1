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
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full" :class="step >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">1</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Service</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center" :class="step >= 2 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full" :class="step >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">2</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Schedule</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 sm:mx-4" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div class="flex items-center" :class="step >= 3 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="flex items-center justify-center w-10 h-10 border-2 rounded-full" :class="step >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white'">
                            <span class="text-sm font-semibold">3</span>
                        </div>
                        <span class="hidden ml-2 text-sm font-medium sm:inline">Details</span>
                    </div>
                </div>
            </div>

            <!-- Modal Content - Include the form from separate file -->
            <div class="p-6 overflow-y-auto max-h-[calc(100vh-250px)]">
                @include('components.appointment-form')
            </div>
        </div>
    </div>
</div>

<script>
function appointmentModal() {
    return {
        open: false,
        step: 1,
        selectedService: '',
        selectedDate: '',
        selectedTime: '',
        agreedToTerms: false,
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        calendarDays: [],
        formData: {
            firstName: '{{ Auth::user()->name ?? "" }}',
            middleInitial: '',
            lastName: '',
            birthdate: '',
            age: '',
            gender: '',
            email: '{{ Auth::user()->email ?? "" }}',
            phoneNumber: '',
            purokNo: '',
            notes: ''
        },
        timeSlots: [
            { value: '08:00', label: '8:00 AM' },
            { value: '09:00', label: '9:00 AM' },
            { value: '10:00', label: '10:00 AM' },
            { value: '11:00', label: '11:00 AM' },
            { value: '13:00', label: '1:00 PM' },
            { value: '14:00', label: '2:00 PM' },
            { value: '15:00', label: '3:00 PM' },
            { value: '16:00', label: '4:00 PM' }
        ],
        
        get currentMonthYear() {
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
            return `${months[this.currentMonth]} ${this.currentYear}`;
        },
        
        openModal() {
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.generateCalendar();
        },
        
        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
            setTimeout(() => {
                this.resetForm();
            }, 300);
        },
        
        resetForm() {
            this.step = 1;
            this.selectedService = '';
            this.selectedDate = '';
            this.selectedTime = '';
            this.agreedToTerms = false;
            this.currentMonth = new Date().getMonth();
            this.currentYear = new Date().getFullYear();
            this.formData = {
                firstName: '{{ Auth::user()->name ?? "" }}',
                middleInitial: '',
                lastName: '',
                birthdate: '',
                age: '',
                gender: '',
                email: '{{ Auth::user()->email ?? "" }}',
                phoneNumber: '',
                purokNo: '',
                notes: ''
            };
        },
        
        selectService(service) {
            this.selectedService = service;
        },
        
        nextStep() {
            if (this.step < 3) {
                this.step++;
            }
        },
        
        previousStep() {
            if (this.step > 1) {
                this.step--;
            }
        },
        
        previousMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.generateCalendar();
        },
        
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.generateCalendar();
        },
        
        generateCalendar() {
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const prevLastDay = new Date(this.currentYear, this.currentMonth, 0);
            const firstDayIndex = firstDay.getDay();
            const lastDayDate = lastDay.getDate();
            const prevLastDayDate = prevLastDay.getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            this.calendarDays = [];
            
            // Previous month's days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const day = prevLastDayDate - i;
                this.calendarDays.push({
                    day: day,
                    isCurrentMonth: false,
                    isPast: true,
                    dateString: '',
                    date: null
                });
            }
            
            // Current month's days
            for (let i = 1; i <= lastDayDate; i++) {
                const date = new Date(this.currentYear, this.currentMonth, i);
                date.setHours(0, 0, 0, 0);
                const isPast = date < today;
                
                this.calendarDays.push({
                    day: i,
                    isCurrentMonth: true,
                    isPast: isPast,
                    dateString: date.toISOString().split('T')[0],
                    date: date
                });
            }
            
            // Next month's days
            const remainingDays = 42 - this.calendarDays.length;
            for (let i = 1; i <= remainingDays; i++) {
                this.calendarDays.push({
                    day: i,
                    isCurrentMonth: false,
                    isPast: false,
                    dateString: '',
                    date: null
                });
            }
        },
        
        selectDate(day) {
            if (day.isCurrentMonth && !day.isPast) {
                this.selectedDate = day.dateString;
            }
        },
        
        calculateAge() {
            if (this.formData.birthdate) {
                const today = new Date();
                const birthDate = new Date(this.formData.birthdate);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                this.formData.age = age;
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

            const appointmentData = {
                service_type: this.selectedService,
                appointment_date: this.selectedDate,
                appointment_time: this.selectedTime,
                first_name: this.formData.firstName,
                middle_initial: this.formData.middleInitial,
                last_name: this.formData.lastName,
                birthdate: this.formData.birthdate,
                age: this.formData.age,
                gender: this.formData.gender,
                email: this.formData.email,
                phone_number: this.formData.phoneNumber,
                purok_no: this.formData.purokNo,
                notes: this.formData.notes,
                _token: '{{ csrf_token() }}'
            };

            console.log('Submitting appointment:', appointmentData);

            try {
                const response = await fetch('{{ route("appointments.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(appointmentData)
                });

                const data = await response.json();
                console.log('Response:', data);

                if (response.ok) {
                    window.location.href = '{{ route("appointments.index") }}';
                } else {
                    const errorMessage = data.message || 'There was an error booking your appointment. Please try again.';
                    alert(errorMessage);
                    console.error('Error details:', data);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('There was an error booking your appointment. Please try again.');
            }
        }
    }
}
</script>