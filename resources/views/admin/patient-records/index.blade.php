@extends('admin.layouts.app')

@section('title', 'Patient Records')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Patient's Records</h2>
        
        <!-- Search -->
        <div class="relative">
            <input type="text" 
                   placeholder="Search" 
                   class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Patient Records Table -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">User ID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Patient Name's</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Age</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Gender</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Last Visit</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($records as $record)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $record['user_id'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record['patient_name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $record['age'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $record['gender'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $record['last_visit'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <button class="px-4 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                    View
                                </button>
                                <button class="px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Edit
                                </button>
                                <button class="px-4 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                    Delete
                                </button>
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