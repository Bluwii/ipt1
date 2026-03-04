@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6" x-data="{ showAddWorker: {{ $errors->any() && old('_from') === 'add_worker' ? 'true' : 'false' }} }">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ $tab === 'workers' ? "Worker's Account Management" : "User's Account Management" }}
        </h2>

        <div class="flex gap-3">
            <!-- Add Worker button — only on Workers tab -->
            @if($tab === 'workers')
            <button @click="showAddWorker = true"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                + Add Worker
            </button>
            @endif

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

    <!-- Validation errors (from Add Worker form) -->
    @if($errors->any())
    <div class="p-4 text-sm text-red-700 bg-red-100 border-l-4 border-red-500 rounded-lg">
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                            {{ $tab === 'workers' ? 'Worker Name' : 'Patient Name' }}
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
                            @if($user['is_active'] ?? true)
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <!-- View -->
                                <a href="{{ route('admin.users.show', ['user' => $user['id']]) }}"
                                   class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    View
                                </a>

                                <!-- Activate / Deactivate -->
                                <form method="POST"
                                      action="{{ route('admin.users.toggle-status', ['user' => $user['id']]) }}"
                                      class="inline">
                                    @csrf @method('PATCH')
                                    @if($user['is_active'] ?? true)
                                        <button type="submit"
                                                onclick="return confirm('Deactivate this account?')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Deactivate
                                        </button>
                                    @else
                                        <button type="submit"
                                                onclick="return confirm('Activate this account?')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                                            Activate
                                        </button>
                                    @endif
                                </form>

                                <!-- Delete -->
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', ['user' => $user['id']]) }}"
                                      class="inline"
                                      onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
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

    <!-- ── Add Worker Modal ──────────────────────────────────────────────── -->
    <div x-show="showAddWorker"
         x-cloak
         @click.self="showAddWorker = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="w-full max-w-md p-8 mx-4 bg-white shadow-2xl rounded-2xl"
             @click.stop>

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Add New Worker</h3>
                <button @click="showAddWorker = false"
                        class="p-2 text-gray-400 rounded-lg hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_from" value="add_worker">

                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. Maria Santos">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="worker@example.com">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Minimum 8 characters">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Re-enter password">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showAddWorker = false"
                            class="px-5 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Create Account
                    </button>
                </div>
            </form>
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