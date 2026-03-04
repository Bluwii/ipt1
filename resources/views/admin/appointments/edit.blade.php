{{--
================================================================================
  ✏️  MODIFY FILE — Replace your existing file with this one
  📁 PATH : resources/views/admin/users/index.blade.php
  📝 DESC : Replaces existing user list — adds Activate/Deactivate toggle and Add Worker/Admin modal
================================================================================
--}}
@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="p-6 space-y-6" x-data="userManager()">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage health workers, admins, and patient accounts</p>
        </div>
        <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Worker / Admin
        </button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="p-4 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-2xl font-bold text-blue-600">{{ $counts['total'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Total Users</p>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-2xl font-bold text-green-600">{{ $counts['active'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Active</p>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-2xl font-bold text-red-500">{{ $counts['inactive'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Deactivated</p>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-2xl font-bold text-purple-600">{{ $counts['workers'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Health Workers</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="p-4 bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <input type="text" x-model="search" @input.debounce.300ms="filterUsers()"
                    placeholder="Search by name, email, or phone..."
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select x-model="roleFilter" @change="filterUsers()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="health_worker">Health Worker</option>
                <option value="patient">Patient</option>
            </select>
            <select x-model="statusFilter" @change="filterUsers()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Deactivated</option>
            </select>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="text-xs tracking-wide text-gray-500 uppercase border-b border-gray-100 bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left">User</th>
                    <th class="px-5 py-3 text-left">Contact</th>
                    <th class="px-5 py-3 text-left">Role</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Joined</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="usersTableBody">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 user-row"
                    data-name="{{ strtolower($user->name) }}"
                    data-email="{{ strtolower($user->email) }}"
                    data-role="{{ $user->role }}"
                    data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center text-sm font-semibold text-blue-700 bg-blue-100 rounded-full h-9 w-9">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' :
                               ($user->role === 'health_worker' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="{{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }} px-2 py-0.5 rounded-full text-xs font-medium">
                            {{ $user->is_active ? 'Active' : 'Deactivated' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Edit --}}
                            <button @click="openEditModal({{ $user->id }})"
                                class="p-1 text-blue-600 transition rounded hover:text-blue-800 hover:bg-blue-50" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            {{-- Toggle Active/Inactive --}}
                            <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50' : 'text-green-600 hover:text-green-800 hover:bg-green-50' }} p-1 rounded transition"
                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}"
                                    onclick="return confirm('{{ $user->is_active ? 'Deactivate this account?' : 'Activate this account?' }}')">
                                    @if($user->is_active)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @endif
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-red-500 transition rounded hover:text-red-700 hover:bg-red-50" title="Delete"
                                    onclick="return confirm('Permanently delete this user?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-3 text-right border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ===================== ADD / EDIT MODAL ===================== --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
         @keydown.escape.window="showModal = false">
        <div class="w-full max-w-lg bg-white shadow-2xl rounded-2xl" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800" x-text="isEditing ? 'Edit User' : 'Add New Worker / Admin'"></h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="isEditing ? `/admin/users/${form.id}` : '/admin/users'" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-600">Full Name *</label>
                        <input type="text" name="name" x-model="form.name" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g. Maria Santos">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Email Address *</label>
                        <input type="email" name="email" x-model="form.email" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@example.com">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Phone Number</label>
                        <input type="tel" name="phone" x-model="form.phone"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="09XX-XXX-XXXX">
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Role *</label>
                        <select name="role" x-model="form.role" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="health_worker">Health Worker</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Status</label>
                        <select name="is_active" x-model="form.is_active"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div x-show="!isEditing" class="col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-600">Password *</label>
                        <input type="password" name="password" :required="!isEditing"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Minimum 8 characters">
                    </div>

                    <div x-show="!isEditing" class="col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-600">Confirm Password *</label>
                        <input type="password" name="password_confirmation" :required="!isEditing"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Re-enter password">
                    </div>

                    <div x-show="isEditing" class="col-span-2">
                        <label class="block mb-1 text-xs font-medium text-gray-600">New Password <span class="text-gray-400">(leave blank to keep current)</span></label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Only fill if changing password">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 transition border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700"
                        x-text="isEditing ? 'Save Changes' : 'Create Account'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function userManager() {
    return {
        showModal: false,
        isEditing: false,
        search: '',
        roleFilter: '',
        statusFilter: '',
        form: {
            id: null, name: '', email: '', phone: '', role: 'health_worker', is_active: '1'
        },

        openAddModal() {
            this.isEditing = false;
            this.form = { id: null, name: '', email: '', phone: '', role: 'health_worker', is_active: '1' };
            this.showModal = true;
        },

        async openEditModal(id) {
            this.isEditing = true;
            this.showModal = true;
            try {
                const res = await fetch(`/admin/users/${id}/edit`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.form = { ...data, is_active: String(data.is_active) };
            } catch(e) {
                console.error(e);
            }
        },

        filterUsers() {
            const rows = document.querySelectorAll('.user-row');
            rows.forEach(row => {
                const name    = row.dataset.name ?? '';
                const email   = row.dataset.email ?? '';
                const role    = row.dataset.role ?? '';
                const status  = row.dataset.status ?? '';
                const q       = this.search.toLowerCase();

                const matchSearch = !q || name.includes(q) || email.includes(q);
                const matchRole   = !this.roleFilter || role === this.roleFilter;
                const matchStatus = !this.statusFilter || status === this.statusFilter;

                row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
            });
        }
    }
}
</script>
@endpush