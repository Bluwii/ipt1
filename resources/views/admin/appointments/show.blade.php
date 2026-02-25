@extends('admin.layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <!-- Back button -->
    <div>
        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
            ← Back to Appointments
        </a>
    </div>

    <div class="p-8 bg-white shadow rounded-2xl">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Appointment Details</h2>
                <p class="mt-1 text-sm text-gray-500">ID #{{ $appointment->id }}</p>
            </div>
            @php $s = $appointment->status; @endphp
            @if($s === 'pending')
                <span class="px-4 py-1.5 text-sm font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
            @elseif($s === 'confirmed')
                <span class="px-4 py-1.5 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
            @elseif($s === 'completed')
                <span class="px-4 py-1.5 text-sm font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
            @else
                <span class="px-4 py-1.5 text-sm font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <!-- Patient Information -->
            <div>
                <h3 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b border-gray-200">Patient Information</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Full Name</dt>
                        <dd class="text-gray-900">{{ $appointment->full_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Age</dt>
                        <dd class="text-gray-900">{{ $appointment->age }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Gender</dt>
                        <dd class="text-gray-900">{{ ucfirst($appointment->gender) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Birthdate</dt>
                        <dd class="text-gray-900">{{ $appointment->birthdate->format('F d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Email</dt>
                        <dd class="text-gray-900">{{ $appointment->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Phone</dt>
                        <dd class="text-gray-900">{{ $appointment->phone_number }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Purok</dt>
                        <dd class="text-gray-900">Purok {{ $appointment->purok_no }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Appointment Information -->
            <div>
                <h3 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b border-gray-200">Appointment Information</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Service Type</dt>
                        <dd class="text-gray-900">{{ $appointment->service_type_label }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Date</dt>
                        <dd class="text-gray-900">{{ $appointment->appointment_date->format('F d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Time</dt>
                        <dd class="text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600">Booked On</dt>
                        <dd class="text-gray-900">{{ $appointment->created_at->format('F d, Y') }}</dd>
                    </div>
                    @if($appointment->notes)
                    <div>
                        <dt class="mb-1 font-medium text-gray-600">Patient Notes</dt>
                        <dd class="p-3 text-gray-900 rounded-lg bg-gray-50">{{ $appointment->notes }}</dd>
                    </div>
                    @endif
                    @if($appointment->admin_notes)
                    <div>
                        <dt class="mb-1 font-medium text-gray-600">Admin Notes</dt>
                        <dd class="p-3 text-gray-900 rounded-lg bg-blue-50">{{ $appointment->admin_notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Action Buttons -->
        @if(in_array($appointment->status, ['pending', 'confirmed']))
        <div class="flex gap-3 pt-6 mt-8 border-t border-gray-200">
            @if($appointment->status === 'pending')
                <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Confirm</button>
                </form>
            @endif
            @if($appointment->status === 'confirmed')
                <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">Mark Complete</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Cancel this appointment?')"
                        class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Cancel Appointment</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection