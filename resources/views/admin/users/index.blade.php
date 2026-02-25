@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ $tab === 'workers' ? "Worker's Account Management" : "User's Account Management" }}
        </h2>

        <div class="flex gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text"
                       id="searchInput"
                       placeholder="Search"
                       class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tab Switcher -->
    <div class="flex border-b border-gray-200">
        <a href="{{ route('admin.users.index', ['tab' => 'patients']) }}"
           class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tab === 'patients' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Patients
        </a>
        <a href="{{ route('admin.users.index', ['tab' => 'workers']) }}"
           class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tab === 'workers' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Workers
        </a>
    </div>

    <!-- User Table -->
    <div class="p-6 bg-white shadow rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full" id="usersTable">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">
                            {{ $tab === 'workers' ? "Worker Name" : "Patient Name" }}
                        </th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Email Address</th>
                        @if($tab === 'workers')
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Role</th>
                        @else
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Phone</th>
                        @endif
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-900">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $user['no'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $tab === 'workers' ? $user['worker_name'] : $user['patient_name'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user['email'] }}</td>
                        @if($tab === 'workers')
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user['role'] }}</td>
                        @else
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user['phone'] }}</td>
                        @endif
                        <td class="px-4 py-3 text-sm">
                            <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                                {{ $user['status'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <!-- View — uses real user ID -->
                                <a href="{{ route('admin.users.show', ['user' => $user['id']]) }}"
                                   class="px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    View
                                </a>

                                <!-- Delete — uses real user ID -->
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', ['user' => $user['id']]) }}"
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-sm text-center text-gray-500">
                            No {{ $tab === 'workers' ? 'workers' : 'patients' }} found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
});
</script>
@endsection