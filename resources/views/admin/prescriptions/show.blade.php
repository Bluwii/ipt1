@extends('admin.layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">

    {{-- Back + Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Appointments
        </a>
        <span class="text-sm text-gray-400">Appointment #{{ $appointment->id }}</span>
    </div>

    {{-- ELIGIBILITY BANNER --}}
    @php
        $age      = (int) ($appointment->age ?? 0);
        $isMinor  = $appointment->is_minor ?? ($age < 18);

        if ($isMinor) {
            $hasGuardian = !empty($appointment->guardian_name) && !empty($appointment->guardian_contact);
            $hasConsent  = (bool) $appointment->guardian_consent;
            $eligible    = $hasGuardian && $hasConsent;
        } else {
            $hasGuardian = false;
            $hasConsent  = false;
            $eligible    = true;
        }
    @endphp

    @if(!$isMinor)
    <div class="flex flex-wrap items-center justify-between gap-4 p-5 border-2 border-green-300 bg-green-50 rounded-xl">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-green-500 rounded-full">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-base font-bold text-green-800">Adult Patient — Eligible</p>
                <p class="text-sm text-green-700 mt-0.5">Patient is {{ $age }} years old — no guardian or parental consent required.</p>
            </div>
        </div>
        <div class="flex flex-col gap-1.5 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-gray-700">Age on record ({{ $age }} yrs)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-gray-700">No guardian required</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="text-gray-700">Self-consent (legal age)</span>
            </div>
        </div>
    </div>

    @elseif($eligible)
    <div class="flex flex-wrap items-center justify-between gap-4 p-5 border-2 bg-amber-50 border-amber-300 rounded-xl">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-amber-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-base font-bold text-amber-800">Minor — Eligible (Guardian Consented)</p>
                <p class="text-sm text-amber-700 mt-0.5">Patient is {{ $age }} years old. Guardian on record — verify they are present at the visit.</p>
            </div>
        </div>
        <div class="flex flex-col gap-1.5 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></span>
                <span class="text-gray-700">Age on record ({{ $age }} yrs)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></span>
                <span class="text-gray-700">Guardian info provided</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></span>
                <span class="text-gray-700">Parent consent on record</span>
            </div>
        </div>
    </div>

    @else
    <div class="flex flex-wrap items-center justify-between gap-4 p-5 border-2 border-red-300 bg-red-50 rounded-xl">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-red-500 rounded-full">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-base font-bold text-red-800">Minor — Incomplete Requirements</p>
                <p class="text-sm text-red-700 mt-0.5">Patient is {{ $age }} years old. Guardian info or consent is missing — do not confirm until resolved.</p>
            </div>
        </div>
        <div class="flex flex-col gap-1.5 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 bg-green-500 rounded-full"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></span>
                <span class="text-gray-700">Age on record ({{ $age }} yrs)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full {{ $hasGuardian ? 'bg-green-500' : 'bg-red-400' }}">
                    @if($hasGuardian)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    @else<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>@endif
                </span>
                <span class="{{ $hasGuardian ? 'text-gray-700' : 'text-red-600' }}">Guardian info {{ $hasGuardian ? 'provided' : 'MISSING' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-5 h-5 rounded-full {{ $hasConsent ? 'bg-green-500' : 'bg-red-400' }}">
                    @if($hasConsent)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    @else<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>@endif
                </span>
                <span class="{{ $hasConsent ? 'text-gray-700' : 'text-red-600' }}">Parent consent {{ $hasConsent ? 'on record' : 'MISSING' }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- MAIN DETAIL CARD --}}
    <div class="overflow-hidden bg-white shadow rounded-2xl">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-8 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $appointment->full_name }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Booked {{ $appointment->created_at->format('F d, Y \a\t g:i A') }}</p>
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

        <div class="grid grid-cols-1 gap-8 p-8 md:grid-cols-2">

            {{-- Patient Info --}}
            <div>
                <h3 class="flex items-center gap-2 mb-4 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Patient Information
                </h3>
                <dl class="space-y-0 text-sm divide-y divide-gray-100">
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Full Name</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->full_name }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Age</dt>
                        <dd class="flex items-center gap-2 font-semibold text-gray-900">
                            {{ $appointment->age }}
                            @if($isMinor)
                                <span class="px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">Minor</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-full">Adult</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Birthdate</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->birthdate->format('F d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Gender</dt>
                        <dd class="font-semibold text-gray-900">{{ ucfirst($appointment->gender) }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->email }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Phone</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->phone_number }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Purok</dt>
                        <dd class="font-semibold text-gray-900">Purok {{ $appointment->purok_no }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Appointment Info --}}
            <div>
                <h3 class="flex items-center gap-2 mb-4 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Appointment Details
                </h3>
                <dl class="space-y-0 text-sm divide-y divide-gray-100">
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Service</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->service_type_label }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Date</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->appointment_date->format('F d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Time Slot</dt>
                        <dd class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Status</dt>
                        <dd>
                            @if($s === 'pending')
                                <span class="px-2 py-0.5 text-xs font-bold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @elseif($s === 'confirmed')
                                <span class="px-2 py-0.5 text-xs font-bold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                            @elseif($s === 'completed')
                                <span class="px-2 py-0.5 text-xs font-bold text-green-800 bg-green-100 rounded-full">Completed</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Booked On</dt>
                        <dd class="font-semibold text-gray-900">{{ $appointment->created_at->format('M d, Y') }}</dd>
                    </div>
                    @if($appointment->notes)
                    <div class="py-2.5">
                        <dt class="mb-1.5 font-medium text-gray-500">Patient Notes</dt>
                        <dd class="p-3 leading-relaxed text-gray-800 rounded-lg bg-gray-50">{{ $appointment->notes }}</dd>
                    </div>
                    @endif
                    @if($appointment->admin_notes)
                    <div class="py-2.5">
                        <dt class="mb-1.5 font-medium text-gray-500">Admin Notes</dt>
                        <dd class="p-3 leading-relaxed text-blue-900 rounded-lg bg-blue-50">{{ $appointment->admin_notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Guardian Section (minors only) --}}
        @if($isMinor)
        <div class="p-5 mx-8 mb-8 border-2 bg-amber-50 border-amber-200 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    <h3 class="text-sm font-bold tracking-wide uppercase text-amber-800">Parent / Guardian Information</h3>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold bg-amber-200 text-amber-800 rounded-full">Must Be Present at Visit</span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm md:grid-cols-4">
                <div class="p-3 bg-white border rounded-lg border-amber-100">
                    <p class="mb-1 text-xs font-bold uppercase text-amber-600">Guardian Name</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->guardian_name ?: '—' }}</p>
                </div>
                <div class="p-3 bg-white border rounded-lg border-amber-100">
                    <p class="mb-1 text-xs font-bold uppercase text-amber-600">Relationship</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst($appointment->guardian_relationship ?: '—') }}</p>
                </div>
                <div class="p-3 bg-white border rounded-lg border-amber-100">
                    <p class="mb-1 text-xs font-bold uppercase text-amber-600">Contact Number</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->guardian_contact ?: '—' }}</p>
                </div>
                <div class="p-3 bg-white border rounded-lg border-amber-100">
                    <p class="mb-1 text-xs font-bold uppercase text-amber-600">Consent Status</p>
                    @if($appointment->guardian_consent)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-full">✓ Consent Given</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-full">✗ No Consent</span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ── PRESCRIPTION REQUEST PANEL (medicine appointments only) ── --}}
        @if($appointment->service_type === 'medicine')
        <div class="mx-8 mb-8">
            <h3 class="flex items-center gap-2 mb-3 text-xs font-bold tracking-wider text-gray-400 uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Prescription Request
            </h3>

            @if($prescriptionRequest)
            <div class="p-5 space-y-3 border-2 border-purple-200 bg-purple-50 rounded-xl">
                {{-- Status badge --}}
                @php $prStatus = $prescriptionRequest->status ?? 'pending'; @endphp
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-sm font-bold text-purple-800">
                        Requested on {{ $prescriptionRequest->created_at->format('F d, Y \a\t g:i A') }}
                    </span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full
                        {{ $prStatus === 'approved' ? 'bg-green-100 text-green-800' :
                           ($prStatus === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $prStatus === 'approved' ? '✓ Approved' : ($prStatus === 'rejected' ? '✗ Rejected' : '⏳ Pending') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    @if($prescriptionRequest->medicine_name)
                    <div class="p-3 bg-white border border-purple-100 rounded-lg">
                        <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Medicine Requested</p>
                        <p class="font-semibold text-gray-900">{{ $prescriptionRequest->medicine_name }}</p>
                    </div>
                    @endif
                    @if($prescriptionRequest->quantity)
                    <div class="p-3 bg-white border border-purple-100 rounded-lg">
                        <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Quantity</p>
                        <p class="font-semibold text-gray-900">{{ $prescriptionRequest->quantity }}</p>
                    </div>
                    @endif
                    @if($prescriptionRequest->dosage)
                    <div class="p-3 bg-white border border-purple-100 rounded-lg">
                        <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Dosage</p>
                        <p class="font-semibold text-gray-900">{{ $prescriptionRequest->dosage }}</p>
                    </div>
                    @endif
                    @if($prescriptionRequest->diagnosis ?? $prescriptionRequest->reason)
                    <div class="p-3 bg-white border border-purple-100 rounded-lg">
                        <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Reason / Diagnosis</p>
                        <p class="font-semibold text-gray-900">{{ $prescriptionRequest->diagnosis ?? $prescriptionRequest->reason }}</p>
                    </div>
                    @endif
                </div>

                @if($prescriptionRequest->notes ?? $prescriptionRequest->description)
                <div class="p-3 text-sm bg-white border border-purple-100 rounded-lg">
                    <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Additional Notes</p>
                    <p class="text-gray-700">{{ $prescriptionRequest->notes ?? $prescriptionRequest->description }}</p>
                </div>
                @endif

                @if($appointment->notes)
                <div class="p-3 text-sm bg-white border border-purple-100 rounded-lg">
                    <p class="mb-1 text-xs font-bold text-purple-500 uppercase">Patient's Appointment Notes</p>
                    <p class="text-gray-700">{{ $appointment->notes }}</p>
                </div>
                @endif

                {{-- Approve/Reject actions if pending --}}
                @if($prStatus === 'pending')
                <div class="flex gap-3 pt-1">
                    <form method="POST" action="{{ route('admin.prescriptions.approve', $prescriptionRequest->id) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            ✓ Approve Request
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.prescriptions.reject', $prescriptionRequest->id) }}">
                        @csrf
                        <input type="hidden" name="reason" value="Rejected by admin">
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-red-500 rounded-lg hover:bg-red-600">
                            ✗ Reject Request
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @else
            <div class="p-5 text-sm text-center text-gray-400 border-2 border-gray-200 border-dashed bg-gray-50 rounded-xl">
                <p>No prescription request linked to this appointment.</p>
                <p class="mt-1 text-xs">The patient may not have submitted a request through the system, or it was submitted without an account.</p>
                @if($appointment->notes)
                <div class="p-3 mt-3 text-left bg-white border border-gray-200 rounded-lg">
                    <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Patient's Notes (from appointment form)</p>
                    <p class="text-gray-700">{{ $appointment->notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        {{-- Action Buttons --}}
        @if(in_array($appointment->status, ['pending', 'confirmed']))
        <div class="flex flex-wrap gap-3 px-8 py-5 border-t border-gray-200 bg-gray-50">
            @if($appointment->status === 'pending')
                <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        ✓ Confirm Appointment
                    </button>
                </form>
            @endif
            @if($appointment->status === 'confirmed')
                <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                        ✔ Mark as Completed
                    </button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to cancel this appointment?')"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    ✕ Cancel Appointment
                </button>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection