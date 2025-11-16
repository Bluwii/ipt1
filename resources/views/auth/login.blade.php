<x-guest-layout>
    <div class="w-full sm:max-w-md">
        <!-- Logo and Title Inside Box -->
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-16 h-16">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Barangay Tambubong</h3>
                <p class="text-sm font-semibold text-gray-900">Health Center</p>
            </div>
        </div>

        <!-- Login Title -->
        <h2 class="mb-6 text-2xl font-bold text-center text-gray-900">Login</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" value="Email" class="mb-2" />
                <x-text-input id="email" 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="Enter your email"
                    required 
                    autofocus 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-2">
                <x-input-label for="password" value="Password" class="mb-2" />
                <x-text-input id="password" 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required 
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Forgot Password Link -->
            @if (Route::has('password.request'))
                <div class="mb-6 text-left">
                    <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                </div>
            @endif

            <!-- Login Button -->
            <button type="submit" class="w-full px-4 py-3 text-base font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Login
            </button>

            <!-- Sign Up Link -->
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600">Don't have an account? </span>
                <a href="{{ route('register') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    Sign-Up
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>