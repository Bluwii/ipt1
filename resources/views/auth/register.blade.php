<x-guest-layout>
    <div class="w-full sm:max-w-2xl">
        <!-- Logo and Title Inside Box -->
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-16 h-16">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Barangay Tambubong</h3>
                <p class="text-sm font-semibold text-gray-900">Health Center</p>
            </div>
        </div>

        <!-- Sign-Up Title -->
        <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Create Your Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Row 1: Full Name and Email -->
            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                <!-- Full Name -->
                <div>
                    <x-input-label for="name" value="Full Name:" class="mb-2 font-semibold" />
                    <x-text-input id="name" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        placeholder="LN/FN/MI"
                        required 
                        autofocus 
                        autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" value="Email:" class="mb-2 font-semibold" />
                    <x-text-input id="email" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        placeholder="Create your email"
                        required 
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <!-- Row 2: Birthdate, Age, and Phone Number -->
            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
                <!-- Birthdate -->
                <div>
                    <x-input-label for="birthdate" value="Birthdate:" class="mb-2 font-semibold" />
                    <x-text-input id="birthdate" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        type="date" 
                        name="birthdate" 
                        :value="old('birthdate')" 
                        required />
                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                </div>

                <!-- Age -->
                <div>
                    <x-input-label for="age" value="Age:" class="mb-2 font-semibold" />
                    <x-text-input id="age" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        type="number" 
                        name="age" 
                        :value="old('age')" 
                        placeholder="Age"
                        min="0"
                        max="120" />
                    <x-input-error :messages="$errors->get('age')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div>
                    <x-input-label for="phone_number" value="Phone Number:" class="mb-2 font-semibold" />
                    <x-text-input id="phone_number" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        type="tel" 
                        name="phone_number" 
                        :value="old('phone_number')" 
                        placeholder="Enter phone number" />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                </div>
            </div>

            <!-- Row 3: Gender and Purok No. -->
            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                <!-- Gender -->
                <div>
                    <x-input-label for="gender" value="Gender:" class="mb-2 font-semibold" />
                    <select id="gender" 
                        name="gender" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Purok No. -->
                <div>
                    <x-input-label for="purok_no" value="Purok No.:" class="mb-2 font-semibold" />
                    <select id="purok_no" 
                        name="purok_no" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                        required>
                        <option value="">Select Purok No.</option>
                        <option value="1" {{ old('purok_no') == '1' ? 'selected' : '' }}>Purok 1</option>
                        <option value="2" {{ old('purok_no') == '2' ? 'selected' : '' }}>Purok 2</option>
                        <option value="3" {{ old('purok_no') == '3' ? 'selected' : '' }}>Purok 3</option>
                        <option value="4" {{ old('purok_no') == '4' ? 'selected' : '' }}>Purok 4</option>
                        <option value="5" {{ old('purok_no') == '5' ? 'selected' : '' }}>Purok 5</option>
                        <option value="6" {{ old('purok_no') == '6' ? 'selected' : '' }}>Purok 6</option>
                    </select>
                    <x-input-error :messages="$errors->get('purok_no')" class="mt-2" />
                </div>
            </div>

            <!-- Row 4: Password (Full Width) -->
            <div class="mb-4">
                <x-input-label for="password" value="Password:" class="mb-2 font-semibold" />
                <x-text-input id="password" 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    type="password"
                    name="password"
                    placeholder="Create your password"
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Row 5: Confirm Password (Full Width) -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" value="Confirm Password:" class="mb-2 font-semibold" />
                <x-text-input id="password_confirmation" 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    type="password"
                    name="password_confirmation" 
                    placeholder="Confirm your password"
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Sign-Up Button -->
            <button type="submit" class="w-full px-4 py-3 text-base font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Sign-Up
            </button>

            <!-- Login Link -->
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600">Already have an account? </span>
                <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>