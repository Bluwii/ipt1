@extends('layouts.app')

@section('title', 'Health Records - Tambubong Health Center')

@section('content')
<!-- Custom Navigation -->
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
                    <svg class="w-4 h-4 text-gray-600 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Appointments</a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="min-h-screen py-8 mt-16 bg-gray-50" x-data="{ prescriptionModal: false }">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Health Records</h1>
                <p class="mt-2 text-gray-600">View your medical history and health information</p>
            </div>
            <!-- Request Prescription Button -->
            <button @click="prescriptionModal = true"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Request Prescription
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
        @endif

        <!-- Tabs -->
        <div x-data="{ activeTab: window.location.hash === '#prescriptions' ? 'prescriptions' : 'consultations' }">
            <div class="flex overflow-x-auto border-b border-gray-200">
                <button @click="activeTab = 'consultations'"
                        :class="activeTab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                    Consultations ({{ $consultations->count() }})
                </button>
                <button @click="activeTab = 'vaccinations'"
                        :class="activeTab === 'vaccinations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
                    Vaccinations ({{ $vaccinations->count() }})
                </button>
                <button @click="activeTab = 'prescriptions'"
                        id="prescriptions"
                        :class="activeTab === 'prescriptions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap">
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
                                <div class="space-y-2 text-sm text-gray-700">
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
                                <div class="space-y-2 text-sm text-gray-700">
                                    <p><span class="font-medium">Date:</span> {{ $record->record_date->format('F d, Y') }}</p>
                                    @if($record->vaccine_name)
                                        <p><span class="font-medium">Vaccine:</span> {{ $record->vaccine_name }}</p>
                                    @endif
                                    @if($record->lot_number)
                                        <p><span class="font-medium">Lot Number:</span> {{ $record->lot_number }}</p>
                                    @endif
                                    @if($record->next_dose_date)
                                        <p><span class="font-medium">Next Dose:</span> {{ $record->next_dose_date->format('F d, Y') }}</p>
                                    @endif
                                    @if($record->notes)
                                        <p class="text-gray-600">{{ $record->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white shadow-md rounded-xl">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Vaccination Records</h3>
                        <p class="text-gray-600">You don't have any vaccination records yet.</p>
                    </div>
                @endif
            </div>

            <!-- Prescriptions Tab -->
            <div x-show="activeTab === 'prescriptions'" class="py-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">Prescription requests submitted for admin approval.</p>
                    <button @click="prescriptionModal = true"
                            class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                        + New Request
                    </button>
                </div>

                @if($prescriptions->count() > 0)
                    <div class="space-y-4">
                        @foreach($prescriptions as $record)
                            <div class="p-6 bg-white shadow-md rounded-xl">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $record->medication_name ?? $record->title }}</h3>
                                        <p class="text-sm text-gray-500">Submitted {{ $record->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($record->approval_status === 'approved')
                                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Approved</span>
                                    @elseif($record->approval_status === 'rejected')
                                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Rejected</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending Review</span>
                                    @endif
                                </div>
                                <div class="space-y-2 text-sm text-gray-700">
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
                                    @if($record->admin_notes && $record->approval_status !== 'pending')
                                        <div class="p-3 mt-3 rounded-lg {{ $record->approval_status === 'approved' ? 'bg-green-50' : 'bg-red-50' }}">
                                            <p class="text-xs font-medium {{ $record->approval_status === 'approved' ? 'text-green-800' : 'text-red-800' }}">
                                                Admin Note: {{ $record->admin_notes }}
                                            </p>
                                        </div>
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
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">No Prescription Requests</h3>
                        <p class="mb-4 text-gray-600">Submit a prescription image for admin approval.</p>
                        <button @click="prescriptionModal = true"
                                class="px-6 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Request Prescription
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ===================== Prescription Request Modal ===================== -->
    <div x-show="prescriptionModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         @keydown.escape.window="prescriptionModal = false">
        <div class="relative w-full max-w-lg mx-4 bg-white shadow-2xl rounded-2xl"
             @click.outside="prescriptionModal = false">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Request Prescription</h3>
                <button @click="prescriptionModal = false" class="p-2 text-gray-400 rounded-lg hover:text-gray-600 hover:bg-gray-100">✕</button>
            </div>

            <!-- Form -->
            <form id="prescriptionForm" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Medicine Name <span class="text-red-500">*</span></label>
                        <input type="text" name="medication_name" required
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Dosage <span class="text-red-500">*</span></label>
                        <input type="text" name="dosage" required placeholder="e.g. 500mg"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Frequency <span class="text-red-500">*</span></label>
                        <input type="text" name="frequency" required placeholder="e.g. 3 times daily"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Duration (days)</label>
                        <input type="number" name="duration_days" min="1" max="365"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Special Instructions</label>
                    <textarea name="instructions" rows="2" maxlength="500"
                              class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="e.g. Take after meals..."></textarea>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">
                        Prescription Image <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-gray-400">(JPG/PNG, max 5MB)</span>
                    </label>
                    <input type="file" name="prescription_image" accept="image/jpeg,image/png,image/jpg" required
                           class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700">
                </div>

                <!-- Error message area -->
                <div id="prescriptionError" class="hidden p-3 text-sm text-red-800 bg-red-100 rounded-lg"></div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="prescriptionModal = false"
                            class="px-5 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="prescriptionSubmitBtn"
                            class="px-5 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('prescriptionForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn      = document.getElementById('prescriptionSubmitBtn');
    const errorDiv = document.getElementById('prescriptionError');
    btn.disabled   = true;
    btn.textContent = 'Submitting…';
    errorDiv.classList.add('hidden');

    const formData = new FormData(this);

    try {
        const response = await fetch('{{ route("prescriptions.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                // FIX: Tell Laravel to return JSON errors instead of a redirect
                // Without this, a validation failure returns HTML (302 redirect)
                // which causes response.json() to crash silently
                'Accept': 'application/json'
                // NOTE: Do NOT set Content-Type here — the browser must set it
                // automatically with the multipart/form-data boundary for file uploads
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Close modal and reload to show new prescription
            window.location.reload();
        } else {
            const messages = data.errors
                ? Object.values(data.errors).flat().join(' ')
                : (data.message || 'Submission failed. Please try again.');
            errorDiv.textContent = messages;
            errorDiv.classList.remove('hidden');
        }
    } catch (err) {
        console.error('Prescription upload error:', err);
        errorDiv.textContent = 'An unexpected error occurred. Please try again.';
        errorDiv.classList.remove('hidden');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Submit Request';
    }
});
</script>
@endsection