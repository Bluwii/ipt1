@extends('admin.layouts.app')

@section('title', 'Appointments Management')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="p-6 bg-white border-2 border-gray-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Total Appointments</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        
        <div class="p-6 bg-white border-2 border-yellow-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Pending:</p>
            <p class="mt-2 text-4xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        
        <div class="p-6 bg-white border-2 border-green-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Complete:</p>
            <p class="mt-2 text-4xl font-bold text-green-600">{{ $stats['complete'] }}</p>
        </div>
        
        <div class="p-6 bg-white border-2 border-red-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Cancel:</p>
            <p class="mt-2 text-4xl font-bold text-red-600">{{ $stats['cancel'] }}</p>
        </div>
    </div>

    <!-- Appointments Section -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Appointments</h2>
            
            <div class="flex gap-3">
                <!-- Search -->
                <div class="relative">
                    <input type="text" 
                           placeholder="Search" 
                           class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <!-- Filter Button -->
                <button class="flex items-center gap-2 px-4 py-2 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                    <span>Filter</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Patient Name's</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Appointment Date/Time</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Type of Service</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment['no'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $appointment['patient_name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['appointment_date'] }} {{ $appointment['appointment_time'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['service_type'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($appointment['status'] === 'Pending')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Complete</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                @if($appointment['status'] === 'Pending')
                                    <!-- Confirm Button -->
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment['id']) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                            Confirm
                                        </button>
                                    </form>
                                    
                                    <!-- Cancel Button -->
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Cancel this appointment?')"
                                                class="px-4 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                            Cancel
                                        </button>
                                    </form>
                                @elseif($appointment['status'] === 'Confirmed')
                                    <!-- Mark Complete Button -->
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment['id']) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                            Mark Complete
                                        </button>
                                    </form>
                                @else
                                    <!-- View Button for Completed/Cancelled -->
                                    <button onclick="alert('Appointment details: {{ $appointment['patient_name'] }} - {{ $appointment['service_type'] }}')" 
                                            class="px-4 py-1.5 text-xs font-semibold text-white bg-gray-600 rounded hover:bg-gray-700">
                                        View
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection