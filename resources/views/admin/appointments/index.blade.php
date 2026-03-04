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
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="mt-2 text-4xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="p-6 bg-white border-2 border-green-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Completed</p>
            <p class="mt-2 text-4xl font-bold text-green-600">{{ $stats['complete'] }}</p>
        </div>
        <div class="p-6 bg-white border-2 border-red-200 shadow rounded-xl">
            <p class="text-sm font-medium text-gray-600">Cancelled</p>
            <p class="mt-2 text-4xl font-bold text-red-600">{{ $stats['cancel'] }}</p>
        </div>
    </div>

    <!-- ── Filter Bar ──────────────────────────────────────────────────── -->
    <div class="p-5 bg-white shadow rounded-xl print:hidden">
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="flex flex-wrap items-end gap-3">
            <!-- Search -->
            <div class="flex-1 min-w-[160px]">
                <label class="block mb-1 text-xs font-semibold text-gray-600">Patient Name</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search..."
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Service -->
            <div class="min-w-[140px]">
                <label class="block mb-1 text-xs font-semibold text-gray-600">Service</label>
                <select name="service" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Services</option>
                    <option value="checkup"  {{ request('service') === 'checkup'  ? 'selected' : '' }}>Check Up</option>
                    <option value="vaccine"  {{ request('service') === 'vaccine'  ? 'selected' : '' }}>Vaccination</option>
                    <option value="medicine" {{ request('service') === 'medicine' ? 'selected' : '' }}>Medicine Request</option>
                </select>
            </div>

            <!-- Status -->
            <div class="min-w-[140px]">
                <label class="block mb-1 text-xs font-semibold text-gray-600">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="min-w-[140px]">
                <label class="block mb-1 text-xs font-semibold text-gray-600">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date To -->
            <div class="min-w-[140px]">
                <label class="block mb-1 text-xs font-semibold text-gray-600">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.appointments.index') }}"
                   class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Clear
                </a>
            </div>
        </form>

        <!-- Active filter badges -->
        @if(request()->anyFilled(['search','service','status','date_from','date_to']))
        <div class="flex flex-wrap items-center gap-2 pt-3 mt-3 border-t border-gray-100">
            <span class="text-xs font-semibold text-gray-500">Active filters:</span>
            @if(request('search'))
                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Name: {{ request('search') }}</span>
            @endif
            @if(request('service'))
                <span class="px-2 py-0.5 text-xs bg-purple-100 text-purple-800 rounded-full">Service: {{ ucfirst(request('service')) }}</span>
            @endif
            @if(request('status'))
                <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded-full">Status: {{ ucfirst(request('status')) }}</span>
            @endif
            @if(request('date_from'))
                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">From: {{ request('date_from') }}</span>
            @endif
            @if(request('date_to'))
                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">To: {{ request('date_to') }}</span>
            @endif
            <span class="ml-auto text-xs text-gray-500">{{ count($appointments) }} result(s)</span>
        </div>
        @endif
    </div>

    <!-- ── Appointments Table ──────────────────────────────────────────── -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex items-center justify-between mb-6 print:hidden">
            <h2 class="text-2xl font-bold text-gray-900">Appointments</h2>
            <button onclick="printReport()"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gray-700 rounded-lg hover:bg-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print / Export
            </button>
        </div>

        {{-- Print-only header --}}
        <div class="hidden mb-6 print:block">
            <h1 class="text-2xl font-bold">Barangay Tambubong Health Center</h1>
            <h2 class="mt-1 text-lg font-semibold">Appointments Report</h2>
            <p class="mt-1 text-sm text-gray-600">Generated: {{ now()->format('F d, Y g:i A') }}</p>
            @if(request()->anyFilled(['search','service','status','date_from','date_to']))
            <p class="text-sm text-gray-600">
                Filters:
                @if(request('search')) Name: {{ request('search') }} @endif
                @if(request('service')) | Service: {{ ucfirst(request('service')) }} @endif
                @if(request('status')) | Status: {{ ucfirst(request('status')) }} @endif
                @if(request('date_from')) | From: {{ request('date_from') }} @endif
                @if(request('date_to')) | To: {{ request('date_to') }} @endif
            </p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="appointmentsTable">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Patient Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Date / Time</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Service</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900 print:hidden">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr class="transition-colors cursor-pointer hover:bg-blue-50"
                        onclick="window.location='{{ route('admin.appointments.show', $appointment['id']) }}'">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment['no'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-2">
                                {{ $appointment['patient_name'] }}
                                @if(!empty($appointment['is_minor']))
                                    <span class="px-1.5 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full print:hidden">👦 Minor</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $appointment['appointment_date'] }}<br>
                            <span class="text-xs text-gray-400">{{ $appointment['appointment_time'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $appointment['service_type'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php $s = $appointment['status']; @endphp
                            @if($s === 'pending')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @elseif($s === 'confirmed')
                                <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                            @elseif($s === 'completed')
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm print:hidden" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.appointments.show', $appointment['id']) }}"
                                   class="px-3 py-1.5 text-xs font-semibold text-white bg-gray-700 rounded hover:bg-gray-900">
                                    View
                                </a>

                                @if($s === 'pending')
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment['id']) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                            Confirm
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Cancel this appointment?')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                            Cancel
                                        </button>
                                    </form>

                                @elseif($s === 'confirmed')
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment['id']) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                            Complete
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Cancel this appointment?')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs italic text-gray-400">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-sm text-center text-gray-500">No appointments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #appointmentsTable, #appointmentsTable * { visibility: visible; }
    .print\:hidden { display: none !important; }
    .print\:block  { display: block  !important; }
    #appointmentsTable { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>

<script>
function printReport() {
    window.print();
}
</script>
@endsection