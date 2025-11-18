<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tambubong Health Center')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 z-50 w-full bg-white shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo with Image -->
                <div class="flex items-center flex-shrink-0 gap-2 sm:gap-3">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-2 sm:gap-3">
                        <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-8 h-8 sm:w-10 sm:h-10">
                        <span class="text-base font-bold text-gray-800 sm:text-xl lg:text-2xl">Tambubong Health Center</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation Links -->
                <div class="items-center hidden space-x-8 md:flex">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('welcome') ? 'text-blue-600' : '' }}">
                        Home
                    </a>
                    <a href="{{ route ('about')}}" class="font-medium text-gray-700 hover:text-blue-600{{ request()->routeIs('about') ? 'text-blue-600' : '' }}">
                        About
                    </a>
                    <a href="{{ route('welcome') }}#services" class="font-medium text-gray-700 hover:text-blue-600">
                        Services
                    </a>
                    <a href="{{ route('contact')}}" class="font-medium text-gray-700 hover:text-blue-600 {{ request()->routeIs('contact') ? 'text-blue-600' : '' }}">
                        Contact Us
                    </a>
                </div>

                <!-- Desktop Auth Links -->
                <div class="items-center hidden space-x-4 md:flex">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-gray-700 hover:text-blue-600">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Sign Up
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg x-show="!mobileMenuOpen" class="block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <!-- Close icon -->
                        <svg x-show="mobileMenuOpen" class="block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             @click.away="mobileMenuOpen = false"
             class="md:hidden"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200 shadow-lg">
                <!-- Mobile Navigation Links -->
                <a href="{{ route('welcome') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('welcome') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Home
                </a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50' : '' }}">
                    About
                </a>
                <a href="{{ route('welcome') }}#services" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50">
                    Services
                </a>
                <a href="{{ route('contact')}}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50 {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Contact Us
                </a>
                
                <!-- Mobile Auth Section -->
                <div class="pt-4 pb-3 border-t border-gray-200">
                    @auth
                        <div class="px-3 py-2">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 mt-3 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="block w-full px-3 py-2 text-base font-medium text-left text-red-600 rounded-md hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-blue-600 hover:bg-gray-50">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 mt-2 text-base font-medium text-center text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @unless(request()->routeIs('dashboard') || request()->routeIs('profile.edit'))
    <footer class="py-8 mt-20 text-white bg-gray-900 sm:py-12">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <h3 class="mb-4 text-lg font-bold sm:text-xl">Tambubong Health Center</h3>
                    <p class="text-sm text-gray-400 sm:text-base">Committed to providing quality healthcare services to our community.</p>
                </div>
                <div>
                    <h4 class="mb-4 text-base font-semibold sm:text-lg">Quick Links</h4>
                    <ul class="space-y-2 text-sm sm:text-base">
                        <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white">About</a></li>
                        <li><a href="{{ route('welcome') }}#services" class="text-gray-400 hover:text-white">Services</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-base font-semibold sm:text-lg">Services</h4>
                    <ul class="space-y-2 text-sm sm:text-base">
                        <li><a href="#services" class="text-gray-400 hover:text-white">Consultations</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-white">Vaccinations</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-white">Health Records</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-base font-semibold sm:text-lg">Contact Info</h4>
                    <ul class="space-y-2 text-sm text-gray-400 sm:text-base">
                        <li>Email: info@tambubong.health</li>
                        <li>Phone: 0991-275-1509</li>
                        <li>Address: Tambubong, San Rafael, Bulacan</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-center border-t border-gray-800">
                <p class="text-sm text-gray-400 sm:text-base">&copy; {{ date('Y') }} Tambubong Health Center. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endunless

    @stack('scripts')
</body>
</html>