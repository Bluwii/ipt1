@extends('admin.layouts.app')

@section('title', 'Patient Health Records')

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <a href="{{ route('admin.patient-records.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
        ← Back to Patient List
    </a>

    <!-- Patient Info Card -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-16 h-16 text-white bg-blue-600 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">User ID: #{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            <!-- Add Record Button -->
            <button onclick="document.getElementById('addRecordModal').classList.remove('hidden')"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                + Add Health Record
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-xs font-medium text-gray-500">Email</p>
                <p class="font-medium text-gray-900">{{ $user->email }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-xs font-medium text-gray-500">Phone</p>
                <p class="font-medium text-gray-900">{{ $user->phone_number ?? 'N/A' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-xs font-medium text-gray-500">Gender</p>
                <p class="font-medium text-gray-900">{{ ucfirst($user->gender ?? 'N/A') }}</p>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <p class="text-xs font-medium text-gray-500">Age / Birthdate</p>
                <p class="font-medium text-gray-900">
                    @if($user->birthdate)
                        {{ \Carbon\Carbon::parse($user->birthdate)->age }} yrs
                        ({{ \Carbon\Carbon::parse($user->birthdate)->format('M d, Y') }})
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg"
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tabs -->
    <div x-data="{ tab: 'consultations' }">
        <div class="flex border-b border-gray-200">
            <button @click="tab = 'consultations'"
                    :class="tab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2">
                Consultations ({{ $consultations->count() }})
            </button>
            <button @click="tab = 'vaccinations'"
                    :class="tab === 'vaccinations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2">
                Vaccinations ({{ $vaccinations->count() }})
            </button>
            <button @click="tab = 'prescriptions'"
                    :class="tab === 'prescriptions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2">
                Prescriptions ({{ $prescriptions->count() }})
            </button>
            <button @click="tab = 'appointments'"
                    :class="tab === 'appointments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2">
                Appointments ({{ $appointments->count() }})
            </button>
        </div>

        <!-- Consultations -->
        <div x-show="tab === 'consultations'" class="py-4">
            @forelse($consultations as $record)
            <div class="p-5 mb-4 bg-white shadow rounded-xl">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $record->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $record->provider_name }} · {{ $record->record_date->format('M d, Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.patient-records.destroy', $record) }}"
                          onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
                    </form>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm md:grid-cols-4">
                    @if($record->diagnosis)
                    <div><span class="font-medium text-gray-600">Diagnosis:</span> <span class="text-gray-900">{{ $record->diagnosis }}</span></div>
                    @endif
                    @if($record->blood_pressure)
                    <div><span class="font-medium text-gray-600">BP:</span> <span class="text-gray-900">{{ $record->blood_pressure }}</span></div>
                    @endif
                    @if($record->temperature)
                    <div><span class="font-medium text-gray-600">Temp:</span> <span class="text-gray-900">{{ $record->temperature }}°C</span></div>
                    @endif
                    @if($record->heart_rate)
                    <div><span class="font-medium text-gray-600">HR:</span> <span class="text-gray-900">{{ $record->heart_rate }} bpm</span></div>
                    @endif
                </div>
                @if($record->notes)
                <p class="mt-2 text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $record->notes }}</p>
                @endif
            </div>
            @empty
            <div class="p-8 text-center bg-white shadow rounded-xl">
                <p class="text-gray-500">No consultation records yet.</p>
            </div>
            @endforelse
        </div>

        <!-- Vaccinations -->
        <div x-show="tab === 'vaccinations'" class="py-4">
            @forelse($vaccinations as $record)
            <div class="p-5 mb-4 bg-white shadow rounded-xl">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $record->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $record->provider_name }} · {{ $record->record_date->format('M d, Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.patient-records.destroy', $record) }}"
                          onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
                    </form>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm md:grid-cols-3">
                    @if($record->vaccine_name)
                    <div><span class="font-medium text-gray-600">Vaccine:</span> <span class="text-gray-900">{{ $record->vaccine_name }}</span></div>
                    @endif
                    @if($record->lot_number)
                    <div><span class="font-medium text-gray-600">Lot #:</span> <span class="text-gray-900">{{ $record->lot_number }}</span></div>
                    @endif
                    @if($record->next_dose_date)
                    <div><span class="font-medium text-gray-600">Next Dose:</span> <span class="text-gray-900">{{ $record->next_dose_date->format('M d, Y') }}</span></div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center bg-white shadow rounded-xl">
                <p class="text-gray-500">No vaccination records yet.</p>
            </div>
            @endforelse
        </div>

        <!-- Prescriptions -->
        <div x-show="tab === 'prescriptions'" class="py-4">
            @forelse($prescriptions as $record)
            <div class="p-5 mb-4 bg-white shadow rounded-xl">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $record->medication_name ?? $record->title }}</h3>
                        <p class="text-sm text-gray-500">Submitted {{ $record->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($record->approval_status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                        @elseif($record->approval_status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Approved</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Rejected</span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm md:grid-cols-3">
                    @if($record->dosage)
                    <div><span class="font-medium text-gray-600">Dosage:</span> <span class="text-gray-900">{{ $record->dosage }}</span></div>
                    @endif
                    @if($record->frequency)
                    <div><span class="font-medium text-gray-600">Frequency:</span> <span class="text-gray-900">{{ $record->frequency }}</span></div>
                    @endif
                    @if($record->duration_days)
                    <div><span class="font-medium text-gray-600">Duration:</span> <span class="text-gray-900">{{ $record->duration_days }} days</span></div>
                    @endif
                </div>
                @if($record->prescription_image)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $record->prescription_image) }}" target="_blank"
                       class="text-sm text-blue-600 hover:underline">📎 View Prescription Image</a>
                </div>
                @endif
            </div>
            @empty
            <div class="p-8 text-center bg-white shadow rounded-xl">
                <p class="text-gray-500">No prescription records yet.</p>
            </div>
            @endforelse
        </div>

        <!-- Appointments -->
        <div x-show="tab === 'appointments'" class="py-4">
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="w-full">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Service</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Date</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Time</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($appointments as $apt)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $apt->service_type_label }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $apt->appointment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('g:i A') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($apt->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                                @elseif($apt->status === 'confirmed')
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                                @elseif($apt->status === 'completed')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="{{ route('admin.appointments.show', $apt) }}"
                                   class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-sm text-center text-gray-500">No appointments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Health Record Modal -->
<div id="addRecordModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Add Health Record</h3>
            <button onclick="document.getElementById('addRecordModal').classList.add('hidden')"
                    class="p-2 text-gray-400 rounded-lg hover:text-gray-600 hover:bg-gray-100">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.patient-records.store') }}" class="p-6 space-y-4"
              x-data="{ recordType: 'consultation' }">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">Record Type</label>
                <select name="record_type" x-model="recordType" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="consultation">Consultation</option>
                    <option value="vaccination">Vaccination</option>
                    <option value="prescription">Prescription</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Provider Name <span class="text-red-500">*</span></label>
                    <input type="text" name="provider_name" required placeholder="Dr. Name"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">Record Date <span class="text-red-500">*</span></label>
                <input type="date" name="record_date" required value="{{ date('Y-m-d') }}"
                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Consultation fields -->
            <div x-show="recordType === 'consultation'" class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Diagnosis</label>
                    <input type="text" name="diagnosis"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Blood Pressure</label>
                        <input type="text" name="blood_pressure" placeholder="120/80 mmHg"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Temperature (°C)</label>
                        <input type="number" name="temperature" step="0.1"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Heart Rate (bpm)</label>
                        <input type="number" name="heart_rate"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Respiratory Rate</label>
                        <input type="number" name="respiratory_rate"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Vaccination fields -->
            <div x-show="recordType === 'vaccination'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Vaccine Name</label>
                        <input type="text" name="vaccine_name"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Lot Number</label>
                        <input type="text" name="lot_number"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Next Dose Date</label>
                    <input type="date" name="next_dose_date"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Prescription fields -->
            <div x-show="recordType === 'prescription'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Medication Name</label>
                        <input type="text" name="medication_name"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Dosage</label>
                        <input type="text" name="dosage" placeholder="500mg"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Frequency</label>
                        <input type="text" name="frequency" placeholder="3 times daily"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Duration (days)</label>
                        <input type="number" name="duration_days" min="1"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Instructions</label>
                    <textarea name="instructions" rows="2"
                              class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <!-- Notes (shared) -->
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">Notes</label>
                <textarea name="notes" rows="2"
                          class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('addRecordModal').classList.add('hidden')"
                        class="px-5 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Save Record
                </button>
            </div>s
        </form>
    </div>
</div>
@endsection