@extends('admin.layouts.app')

@section('title', 'Appointments Management')

@section('content')
<div class="space-y-6">

    {{-- ── Stats Cards ── --}}
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

    {{-- ── Main Table Card ── --}}
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Appointments</h2>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print / Export
            </button>
        </div>

        {{-- ── Filter Bar ── --}}
        <form id="filterForm" method="GET" action="{{ route('admin.appointments.index') }}"
              class="flex flex-wrap items-end gap-3 p-4 mb-5 border border-gray-200 bg-gray-50 rounded-xl no-print">
            <div class="flex-1 min-w-[180px]">
                <label class="block mb-1 text-xs font-medium text-gray-600">Search Patient</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Patient name..."
                           oninput="clearTimeout(window._st); window._st = setTimeout(() => document.getElementById('filterForm').submit(), 500)"
                           class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg pl-9 focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute w-4 h-4 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="min-w-[150px]">
                <label class="block mb-1 text-xs font-medium text-gray-600">Service Type</label>
                <select name="service" onchange="document.getElementById('filterForm').submit()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Services</option>
                    <option value="checkup"  {{ request('service') === 'checkup'  ? 'selected' : '' }}>Check Up</option>
                    <option value="vaccine"  {{ request('service') === 'vaccine'  ? 'selected' : '' }}>Vaccine</option>
                    <option value="medicine" {{ request('service') === 'medicine' ? 'selected' : '' }}>Request Medicine</option>
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="block mb-1 text-xs font-medium text-gray-600">Status</label>
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value=""          {{ !request('status')                     ? 'selected':'' }}>All Status</option>
                    <option value="pending"   {{ request('status') === 'pending'        ? 'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed'      ? 'selected':'' }}>Confirmed</option>
                    <option value="completed" {{ request('status') === 'completed'      ? 'selected':'' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled'      ? 'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block mb-1 text-xs font-medium text-gray-600">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       onchange="document.getElementById('filterForm').submit()"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-[150px]">
                <label class="block mb-1 text-xs font-medium text-gray-600">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       onchange="document.getElementById('filterForm').submit()"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.appointments.index') }}"
                   class="px-4 py-2 text-sm font-semibold text-gray-600 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>

        {{-- Active filter badges --}}
        @if(request()->hasAny(['search','service','status','date_from','date_to']))
        <div class="flex flex-wrap gap-2 mb-4 text-xs no-print">
            <span class="self-center text-gray-500">Filters:</span>
            @if(request('search'))<span class="px-2 py-1 text-blue-700 bg-blue-100 rounded-full">Search: {{ request('search') }}</span>@endif
            @if(request('service'))<span class="px-2 py-1 text-purple-700 bg-purple-100 rounded-full">{{ ucfirst(request('service')) }}</span>@endif
            @if(request('status') && request('status') !== 'all')<span class="px-2 py-1 text-yellow-700 bg-yellow-100 rounded-full">{{ ucfirst(request('status')) }}</span>@endif
            @if(request('date_from') || request('date_to'))<span class="px-2 py-1 text-green-700 bg-green-100 rounded-full">{{ request('date_from','…') }} → {{ request('date_to','…') }}</span>@endif
            <span class="self-center text-gray-400">{{ count($appointments) }} result(s)</span>
        </div>
        @endif

        {{-- Print header (hidden on screen) --}}
        <div class="hidden mb-6 text-center print-only">
            <h1 class="text-xl font-bold">Tambubong Health Center — Appointments Report</h1>
            <p class="text-sm text-gray-500">Generated: {{ now()->format('F d, Y h:i A') }}</p>
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
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900 no-print">Action</th>
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
                                    <span class="px-1.5 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">👦 Minor</span>
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
                            @if($s==='pending')    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @elseif($s==='confirmed') <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmed</span>
                            @elseif($s==='completed') <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                            @else <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm no-print" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.appointments.show', $appointment['id']) }}"
                                   class="px-3 py-1.5 text-xs font-semibold text-white bg-gray-700 rounded hover:bg-gray-900">View</a>
                                @if($s==='pending')
                                    <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment['id']) }}" class="inline">
                                        @csrf @method('PATCH')<input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">Confirm</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Cancel this appointment?')" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">Cancel</button>
                                    </form>
                                @elseif($s==='confirmed')
                                    <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment['id']) }}" class="inline">
                                        @csrf @method('PATCH')<input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">Complete</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Cancel this appointment?')" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">Cancel</button>
                                    </form>
                                @else
                                    <span class="text-xs italic text-gray-400">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-sm text-center text-gray-500">No appointments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    nav, aside, header, footer { display: none !important; }
    body { font-size: 12px; }
    .shadow, .rounded-xl { box-shadow: none !important; border-radius: 0 !important; }
}
</style>
@endsection