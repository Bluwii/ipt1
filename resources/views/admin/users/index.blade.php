@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,3500)"
             class="flex items-center gap-3 px-4 py-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ $tab === 'workers' ? "Worker's Account Management" : "User's Account Management" }}
        </h2>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search"
                       class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute w-5 h-5 text-gray-400 left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @if($tab === 'workers')
                <button onclick="document.getElementById('createWorkerModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Worker
                </button>
            @endif
        </div>
    </div>

    {{-- ── Tabs ── --}}
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

    {{-- ── Table ── --}}
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
                            @if($user['is_active'])
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user['id']) }}"
                                   class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                    View
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.users.toggle-status', $user['id']) }}"
                                      class="inline">
                                    @csrf @method('PATCH')
                                    @php $active = $user['is_active'] ?? true; @endphp
                                    <button type="submit"
                                            data-confirm="{{ $active ? 'Deactivate' : 'Activate' }} this account?"
                                            onclick="return confirm(this.dataset.confirm)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded transition-colors {{ $active ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }}">
                                        {{ $active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user['id']) }}"
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
</div>

{{-- ══ CREATE WORKER MODAL ══ --}}
<div id="createWorkerModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40">
    <div class="w-full max-w-md p-6 mx-4 bg-white shadow-xl rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">Add New Worker Account</h2>
            <button onclick="document.getElementById('createWorkerModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @if($errors->any())
            <div class="px-4 py-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                <ul class="space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Maria Santos"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="worker@example.com"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8" placeholder="Minimum 8 characters"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required minlength="8" placeholder="Re-enter password"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('createWorkerModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
});
@if($errors->any())
    document.getElementById('createWorkerModal').classList.remove('hidden');
@endif
</script>
@endsection