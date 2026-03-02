@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_patients'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">Registered users</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Appointments Today</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['today_appointments'] }}</p>
                    <p class="mt-1 text-xs text-blue-600">Today</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Appointments</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['completed_appointments'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">All time</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Prescriptions</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['pending_prescriptions'] }}</p>
                    <p class="mt-1 text-xs text-yellow-600">Awaiting review</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Notifications Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Patient's Chart (2/3 width) -->
        <div class="p-6 bg-white shadow lg:col-span-2 rounded-xl">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Patient's Chart (Last 6 Months)</h2>
            <canvas id="patientsChart" height="80"></canvas>
        </div>

        <!-- Updates / Notifications (1/3 width) -->
        <div class="p-6 bg-white shadow rounded-xl">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Recent Activity</h2>
            <div class="space-y-3">
                @forelse($notifications as $notification)
                <div class="flex items-start gap-3 p-3 transition-colors rounded-lg hover:bg-gray-50">
                    @if($notification['type'] === 'prescription')
                    <div class="flex items-center justify-center flex-shrink-0 bg-yellow-100 rounded-full w-9 h-9">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    @else
                    <div class="flex items-center justify-center flex-shrink-0 bg-blue-100 rounded-full w-9 h-9">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $notification['user'] }}</p>
                        <p class="text-xs text-gray-600">{{ $notification['message'] }}</p>
                        <p class="text-xs text-gray-400">{{ $notification['time'] }}</p>
                    </div>
                    @if($notification['type'] === 'prescription')
                    <a href="{{ route('admin.prescriptions.index') }}"
                       class="flex-shrink-0 text-xs font-medium text-yellow-600 hover:text-yellow-800">Review</a>
                    @endif
                </div>
                @empty
                <p class="py-4 text-sm text-center text-gray-500">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Recent Appointments</h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">No.</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Patient Name</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">User ID</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">Service</th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentAppointments as $appointment)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment['no'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $appointment['patient_name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['appointment_date'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php $s = $appointment['status']; @endphp
                            @if($s === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @elseif($s === 'confirmed')
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                            @elseif($s === 'completed')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['user_id'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['service'] }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <a href="{{ route('admin.appointments.show', $appointment['id']) }}"
                               class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-sm text-center text-gray-500">No appointments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('patientsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Check Up',
                    data: {!! json_encode($chartData['checkups']) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Vaccine',
                    data: {!! json_encode($chartData['vaccines']) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Medicine',
                    data: {!! json_encode($chartData['medicine']) !!},
                    borderColor: 'rgb(139, 92, 246)',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: true, position: 'bottom' } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush