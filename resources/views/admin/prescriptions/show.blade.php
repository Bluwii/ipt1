@extends('admin.layouts.app')

@section('title', 'Prescription Details')

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.prescriptions.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Prescriptions
        </a>
        <span class="text-sm text-gray-400">Prescription #{{ $record->id }}</span>
    </div>

    {{-- Status Banner --}}
    @php $status = $record->approval_status ?? 'pending'; @endphp
    <div class="flex items-center gap-4 p-5 rounded-xl border-2
        {{ $status === 'approved' ? 'bg-green-50 border-green-300' :
           ($status === 'rejected' ? 'bg-red-50 border-red-300' : 'bg-yellow-50 border-yellow-300') }}">
        <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0
            {{ $status === 'approved' ? 'bg-green-500' : ($status === 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
            @if($status === 'approved')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            @elseif($status === 'rejected')
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
        </div>
        <div>
            <p class="font-bold text-base
                {{ $status === 'approved' ? 'text-green-800' : ($status === 'rejected' ? 'text-red-800' : 'text-yellow-800') }}">
                {{ $status === 'approved' ? 'Approved' : ($status === 'rejected' ? 'Rejected' : 'Pending Review') }}
            </p>
            <p class="text-sm mt-0.5
                {{ $status === 'approved' ? 'text-green-700' : ($status === 'rejected' ? 'text-red-700' : 'text-yellow-700') }}">
                Submitted {{ $record->created_at->format('F d, Y \a\t g:i A') }}
            </p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="overflow-hidden bg-white shadow rounded-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between px-8 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $record->medication_name ?? $record->title ?? 'Prescription Request' }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Patient: {{ $record->user->name ?? 'Unknown' }}</p>
            </div>
            <span class="px-4 py-1.5 text-sm font-bold rounded-full
                {{ $status === 'approved' ? 'bg-green-100 text-green-800' :
                   ($status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst($status) }}
            </span>
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
                        <dt class="font-medium text-gray-500">Name</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Age</dt>
                        <dd class="font-semibold text-gray-900">
                            @if($record->user && $record->user->birthdate)
                                {{ \Carbon\Carbon::parse($record->user->birthdate)->age }} yrs
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Submitted</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Record Date</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->record_date->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Prescription Details --}}
            <div>
                <h3 class="flex items-center gap-2 mb-4 text-xs font-bold tracking-wider text-gray-400 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Prescription Details
                </h3>
                <dl class="space-y-0 text-sm divide-y divide-gray-100">
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Medication</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->medication_name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Dosage</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->dosage ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Frequency</dt>
                        <dd class="font-semibold text-gray-900">{{ $record->frequency ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <dt class="font-medium text-gray-500">Duration</dt>
                        <dd class="font-semibold text-gray-900">
                            {{ $record->duration_days ? $record->duration_days . ' days' : '—' }}
                        </dd>
                    </div>
                    @if($record->instructions)
                    <div class="py-2.5">
                        <dt class="font-medium text-gray-500 mb-1.5">Instructions</dt>
                        <dd class="p-3 leading-relaxed text-gray-800 rounded-lg bg-gray-50">{{ $record->instructions }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Prescription Image --}}
        @if($record->prescription_image)
        <div class="px-8 pb-6">
            <h3 class="mb-3 text-xs font-bold tracking-wider text-gray-400 uppercase">Uploaded Prescription Image</h3>
            <a href="{{ asset('storage/' . $record->prescription_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $record->prescription_image) }}"
                     alt="Prescription"
                     class="transition border border-gray-200 shadow-sm max-h-64 rounded-xl hover:opacity-90">
            </a>
        </div>
        @endif

        {{-- Admin Notes (if rejected) --}}
        @if($status === 'rejected' && $record->admin_notes)
        <div class="px-8 pb-6">
            <div class="p-4 border border-red-200 bg-red-50 rounded-xl">
                <p class="mb-1 text-xs font-bold text-red-500 uppercase">Rejection Reason</p>
                <p class="text-sm text-red-800">{{ $record->admin_notes }}</p>
            </div>
        </div>
        @endif

        {{-- Action Buttons (pending only) --}}
        @if($status === 'pending')
        <div class="flex flex-wrap gap-3 px-8 py-5 border-t border-gray-200 bg-gray-50">
            <form method="POST" action="{{ route('admin.prescriptions.approve', $record->id) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Approve this prescription request?')"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                    ✓ Approve Request
                </button>
            </form>

            <button onclick="document.getElementById('rejectPanel').classList.toggle('hidden')"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                ✗ Reject Request
            </button>
        </div>

        {{-- Reject form panel --}}
        <div id="rejectPanel" class="hidden px-8 pb-6">
            <form method="POST" action="{{ route('admin.prescriptions.reject', $record->id) }}"
                  class="p-5 space-y-3 border-2 border-red-200 bg-red-50 rounded-xl">
                @csrf
                <label class="block text-sm font-semibold text-red-800">Reason for Rejection <span class="text-red-500">*</span></label>
                <textarea name="reason" required rows="3"
                          class="w-full px-4 py-2 text-sm border border-red-300 rounded-lg focus:ring-2 focus:ring-red-400"
                          placeholder="Explain why this prescription is being rejected..."></textarea>
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                    <button type="button"
                            onclick="document.getElementById('rejectPanel').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection