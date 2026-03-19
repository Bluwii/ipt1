@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="space-y-6">
    <div>
    <a href="{{ route('admin.users.show', $user) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition-colors hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Profile 
        </a>
    </div>

    <div class="p-8 bg-white shadow rounded-2xl">
        <div class="flex items-center gap-4 mb-8">
            <div class="flex items-center justify-center w-16 h-16 text-2xl font-bold text-white bg-blue-600 rounded-full">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
            <ul class="space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block mb-1.5 text-sm font-semibold text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1.5 text-sm font-semibold text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone_number" class="block mb-1.5 text-sm font-semibold text-gray-700">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number"
                           value="{{ old('phone_number', $user->phone_number) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Birthdate -->
                <div>
                    <label for="birthdate" class="block mb-1.5 text-sm font-semibold text-gray-700">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate"
                           value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block mb-1.5 text-sm font-semibold text-gray-700">Gender</label>
                    <select id="gender" name="gender"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">— Select —</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Purok No -->
                <div>
                    <label for="purok_no" class="block mb-1.5 text-sm font-semibold text-gray-700">Purok No.</label>
                    <input type="text" id="purok_no" name="purok_no"
                           value="{{ old('purok_no', $user->purok_no) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block mb-1.5 text-sm font-semibold text-gray-700">Role</label>
                    <select id="role" name="role"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="patient" {{ old('role', $user->role) === 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="mt-1 text-xs text-gray-400">You cannot change your own role.</p>
                    @endif
                </div>
            </div>

            <!-- Password Section -->
            <div class="pt-6 mt-8 border-t border-gray-100">
                <h3 class="mb-4 text-base font-semibold text-gray-700">Change Password <span class="text-xs font-normal text-gray-400">(leave blank to keep current)</span></h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="password" class="block mb-1.5 text-sm font-semibold text-gray-700">New Password</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-1.5 text-sm font-semibold text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-gray-100">
                <a href="{{ route('admin.users.show', $user) }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection