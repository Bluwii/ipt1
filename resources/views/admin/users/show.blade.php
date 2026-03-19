@extends('admin.layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="space-y-6">

    <!-- Back -->
    <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Users
        </a>

    <!-- Profile Card -->
    <div class="p-8 bg-white shadow rounded-2xl">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 text-xl font-bold text-white bg-blue-600 rounded-full">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <p class="mt-1 text-xs text-gray-400">Member since {{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold {{ $user->role === 'admin' ? 'text-purple-800 bg-purple-100' : 'text-blue-800 bg-blue-100' }} rounded-full">
                    {{ ucfirst($user->role) }}
                </span>
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Edit Profile
                </a>
                @if(auth()->id() !== $user->id)
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete this user? All their data will be permanently removed.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Personal Info Grid -->
        <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-100 md:grid-cols-3">
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">Birthdate</p>
                <p class="mt-1 text-gray-900">
                    @if($user->birthdate)
                        {{ $user->birthdate->format('F d, Y') }}
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">Age</p>
                <p class="mt-1 text-gray-900">
                    @if($user->birthdate)
                        {{ $user->birthdate->age }} years old
                    @elseif($user->age)
                        {{ $user->age }} years old
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">Gender</p>
                <p class="mt-1 text-gray-900">{{ ucfirst($user->gender ?? '') ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">Phone Number</p>
                <p class="mt-1 text-gray-900">{{ $user->phone_number ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">Purok</p>
                <p class="mt-1 text-gray-900">{{ $user->purok_no ? 'Purok ' . $user->purok_no : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium tracking-wider text-gray-400 uppercase">User ID</p>
                <p class="mt-1 font-mono text-gray-900">{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="flex items-center gap-4 p-4 bg-white shadow rounded-xl">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Appointments</p>
                <p class="text-2xl font-bold text-gray-900">{{ $appointments->count() }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-4 bg-white shadow rounded-xl">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $appointments->where('status', 'completed')->count() }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-4 bg-white shadow rounded-xl">
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Health Records</p>
                <p class="text-2xl font-bold text-gray-900">{{ $healthRecords->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Appointments -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Appointment History</h3>
            <a href="{{ route('admin.appointments.index') }}" class="text-sm text-blue-600 hover:underline">
                View All Appointments
            </a>
        </div>

        @if($appointments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold text-left text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-xs font-semibold text-left text-gray-600 uppercase">Service</th>
                        <th class="px-4 py-3 text-xs font-semibold text-left text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold text-left text-gray-600 uppercase">Notes</th>
                        <th class="px-4 py-3 text-xs font-semibold text-center text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($appointments as $apt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">
                            <p class="font-medium text-gray-900">{{ $apt->appointment_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('g:i A') }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $apt->service_type_label }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($apt->status === 'completed')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                            @elseif($apt->status === 'confirmed')
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                            @elseif($apt->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $apt->notes ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <a href="{{ route('admin.appointments.show', $apt) }}"
                               class="text-xs font-medium text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="py-8 text-center text-gray-500">No appointments found.</p>
        @endif
    </div>

    <!-- Health Records -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Health Records</h3>
            <a href="{{ route('admin.patient-records.patient', $user) }}"
               class="text-sm text-blue-600 hover:underline">
                View Full Records
            </a>
        </div>

        @if($healthRecords->count() > 0)
        <div class="space-y-3">
            @foreach($healthRecords as $record)
            <div class="flex items-start justify-between p-4 transition-colors border border-gray-100 rounded-lg hover:border-gray-200">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                            {{ $record->record_type === 'consultation' ? 'bg-blue-100 text-blue-800' : ($record->record_type === 'vaccination' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ $record->record_type_label }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $record->record_date->format('M d, Y') }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-900">{{ $record->title }}</p>
                    <p class="text-xs text-gray-500">{{ $record->provider_name }}</p>
                </div>
                @if($record->record_type === 'prescription')
                    @if($record->approval_status === 'approved')
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Approved</span>
                    @elseif($record->approval_status === 'rejected')
                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Rejected</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                    @endif
                @endif
            </div>
            @endforeach
        </div>
        @else
            <p class="py-8 text-center text-gray-500">No health records found.</p>
        @endif
    </div>

</div>
@endsection