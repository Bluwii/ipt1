@extends('admin.layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.appointments.show', $appointment) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Appointment
        </a>
        <span class="text-sm text-gray-400">Appointment #{{ $appointment->id }}</span>
    </div>

    {{-- Errors --}}
    @if($errors->any())
    <div class="p-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="overflow-hidden bg-white shadow rounded-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between px-8 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit Appointment</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $appointment->full_name }} — Booked {{ $appointment->created_at->format('F d, Y') }}
                </p>
            </div>
            @php $s = $appointment->status; @endphp
            @if($s === 'pending')
                <span class="px-4 py-1.5 text-sm font-bold text-yellow-800 bg-yellow-100 rounded-full">⏳ Pending</span>
            @elseif($s === 'confirmed')
                <span class="px-4 py-1.5 text-sm font-bold text-blue-800 bg-blue-100 rounded-full">✓ Confirmed</span>
            @elseif($s === 'completed')
                <span class="px-4 py-1.5 text-sm font-bold text-green-800 bg-green-100 rounded-full">✔ Completed</span>
            @else
                <span class="px-4 py-1.5 text-sm font-bold text-red-800 bg-red-100 rounded-full">✕ Cancelled</span>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="p-8 space-y-8">
            @csrf
            @method('PATCH')

            {{-- Section 1: Schedule --}}
            <div>
                <h3 class="flex items-center gap-2 mb-5 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Appointment Schedule
                </h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    <div>
                        <label for="service_type" class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Service Type <span class="text-red-500">*</span>
                        </label>
                        <select id="service_type" name="service_type" required
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="checkup"  {{ old('service_type', $appointment->service_type) === 'checkup'  ? 'selected' : '' }}>Check Up</option>
                            <option value="vaccine"  {{ old('service_type', $appointment->service_type) === 'vaccine'  ? 'selected' : '' }}>Vaccine</option>
                            <option value="medicine" {{ old('service_type', $appointment->service_type) === 'medicine' ? 'selected' : '' }}>Request Medicine</option>
                        </select>
                    </div>

                    {{-- Specific Service dropdown — options depend on selected service_type --}}
                    <div x-data="{ stype: '{{ old('service_type', $appointment->service_type) }}' }">
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Specific Service <span class="text-red-500">*</span>
                        </label>
                        @php
                            $serviceOptions = \App\Models\Appointment::serviceOptions();
                            $currentType    = old('service_type', $appointment->service_type);
                            $currentSpecific = old('specific_service', $appointment->specific_service);
                        @endphp
                        <select id="specific_service" name="specific_service"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select specific service --</option>
                            @foreach($serviceOptions[$currentType] ?? [] as $option)
                                <option value="{{ $option }}" {{ $currentSpecific === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">Options are based on the selected service type above.</p>
                    </div>

                    <div>
                        <label for="appointment_date" class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Appointment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="appointment_date" name="appointment_date" required
                               value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="appointment_time" class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Time Slot <span class="text-red-500">*</span>
                        </label>
                        <select id="appointment_time" name="appointment_time" required
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @php
                                $slots = ['08:00 AM','08:30 AM','09:00 AM','09:30 AM','10:00 AM','10:30 AM',
                                          '11:00 AM','11:30 AM','01:00 PM','01:30 PM','02:00 PM','02:30 PM',
                                          '03:00 PM','03:30 PM','04:00 PM','04:30 PM'];
                            @endphp
                            @foreach($slots as $slot)
                            <option value="{{ $slot }}"
                                {{ old('appointment_time', $appointment->appointment_time) === $slot ? 'selected' : '' }}>
                                {{ $slot }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            {{-- Section 2: Status --}}
            <div class="pt-6 border-t border-gray-100">
                <h3 class="flex items-center gap-2 mb-5 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status
                </h3>
                <div class="max-w-xs">
                    <select id="status" name="status"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending"   {{ old('status', $appointment->status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            {{-- Section 3: Notes --}}
            <div class="pt-6 border-t border-gray-100">
                <h3 class="flex items-center gap-2 mb-5 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                    </svg>
                    Notes
                </h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Patient Notes
                            <span class="ml-1 text-xs font-normal text-gray-400">(read-only)</span>
                        </label>
                        <textarea rows="3" readonly
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed resize-none">{{ $appointment->notes ?? '' }}</textarea>
                    </div>

                    <div>
                        <label for="admin_notes" class="block mb-1.5 text-sm font-semibold text-gray-700">
                            Admin Notes
                            <span class="ml-1 text-xs font-normal text-gray-400">(staff only)</span>
                        </label>
                        <textarea id="admin_notes" name="admin_notes" rows="3"
                                  placeholder="Internal notes for staff..."
                                  class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('admin_notes', $appointment->admin_notes ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('admin.appointments.show', $appointment) }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
@endsection