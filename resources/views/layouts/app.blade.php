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
    <nav class="fixed top-0 z-50 w-full bg-white shadow-sm">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="text-2xl font-bold text-gray-800">
                        Tambubong Health Center
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="items-center hidden space-x-8 md:flex">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('welcome') ? 'text-blue-600' : '' }}">
                        Home
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('about') ? 'text-blue-600' : '' }}">
                        About
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('services') ? 'text-blue-600' : '' }}">
                        Services
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('contact') ? 'text-blue-600' : '' }}">
                        Contact Us
                    </a>
                </div>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600">
                                Dashboard
                            </a>
                        @endif
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
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="py-12 mt-20 text-white bg-gray-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 mb-8 md:grid-cols-4">
                <div>
                    <h3 class="mb-4 text-xl font-bold">Tambubong Health Center</h3>
                    <p class="text-gray-400">Committed to providing quality healthcare services to our community.</p>
                </div>
                <div>
                    <h4 class="mb-4 text-lg font-semibold">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Services</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-lg font-semibold">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Consultations</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Vaccinations</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Health Records</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Appointments</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-lg font-semibold">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@tambubong.health</li>
                        <li>Phone: (123) 456-7890</li>
                        <li>Address: Tambubong, Philippines</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-center border-t border-gray-800">
                <p class="text-gray-400">&copy; {{ date('Y') }} Tambubong Health Center. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>