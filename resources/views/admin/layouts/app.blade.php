<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Tambubong Health Center</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        #sidebar { transition: width 0.25s ease; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100" x-data="adminLayout()" @keydown.escape.window="notifOpen = false">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar"
               :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden'"
               class="flex-shrink-0 bg-white shadow-lg">
            <div class="flex flex-col w-64 h-full">

                <!-- Logo Section -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-full">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Barangay Tambubong</h3>
                            <h3 class="text-sm font-semibold text-gray-900">Health Center</h3>
                        </div>
                    </div>
                    <!-- Admin Badge -->
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">ADMIN</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.appointments.index') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.appointments.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Appointments
                        @php $pa = \App\Models\Appointment::where('status', 'pending')->count(); @endphp
                        @if($pa > 0)
                        <span class="ml-auto px-2 py-0.5 text-xs font-bold {{ request()->routeIs('admin.appointments.*') ? 'text-blue-600 bg-white' : 'text-white bg-yellow-500' }} rounded-full">{{ $pa }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.patient-records.index') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.patient-records.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Patient Records
                    </a>

                    <a href="{{ route('admin.data-records.index') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.data-records.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Data Records
                    </a>

                    <a href="{{ route('admin.prescriptions.index') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.prescriptions.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Prescription Requests
                    </a>

                    <!-- User Management Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center justify-between w-full gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center gap-3">
                                <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                User Management
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-2 ml-8 space-y-2">
                            <a href="{{ route('admin.users.index', ['tab' => 'patients']) }}"
                               class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->input('tab') === 'patients' || (!request()->has('tab') && request()->routeIs('admin.users.*')) ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                                Patient
                            </a>
                            <a href="{{ route('admin.users.index', ['tab' => 'workers']) }}"
                               class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->input('tab') === 'workers' ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                                Workers
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full gap-3 px-4 py-3 text-sm font-medium text-red-600 transition-colors rounded-lg hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- Top Header Bar -->
            <header class="flex-shrink-0 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">

                    <!-- Left: Hamburger + Title -->
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="p-1.5 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors focus:outline-none"
                                :aria-expanded="sidebarOpen"
                                aria-label="Toggle sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-bold text-gray-900">Barangay Tambubong Health Center</h1>
                    </div>

                    <!-- Right: Notification Bell -->
                    <div class="relative" @click.outside="notifOpen = false">
                        @php
                            $pendingCount = \App\Models\Appointment::where('status', 'pending')->count();
                        @endphp
                        <button @click="notifOpen = !notifOpen"
                                class="relative p-2 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 focus:outline-none"
                                aria-label="Notifications">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($pendingCount > 0)
                            <span class="absolute flex items-center justify-center w-4 h-4 text-xs font-bold leading-none text-white bg-red-500 rounded-full top-1 right-1">
                                {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                            </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notifOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 z-50 mt-2 overflow-hidden bg-white shadow-xl w-80 rounded-xl ring-1 ring-black ring-opacity-5">

                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                                @if($pendingCount > 0)
                                <span class="px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $pendingCount }} pending</span>
                                @endif
                            </div>

                            <div class="overflow-y-auto max-h-80">
                                @php
                                    $recentNotifs = \App\Models\Appointment::with('user')
                                        ->where('status', 'pending')
                                        ->orderBy('created_at', 'desc')
                                        ->take(8)
                                        ->get();
                                @endphp

                                @forelse($recentNotifs as $notif)
                                <a href="{{ route('admin.appointments.show', $notif->id) }}"
                                   class="flex items-start gap-3 px-4 py-3 transition-colors border-b hover:bg-blue-50 border-gray-50 last:border-0">
                                    <div class="flex items-center justify-center flex-shrink-0 w-9 h-9 bg-yellow-100 rounded-full mt-0.5">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $notif->full_name }}</p>
                                        <p class="text-xs text-gray-600">{{ $notif->service_type_label }} — Pending review</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="flex-shrink-0 px-2 py-0.5 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                                </a>
                                @empty
                                <div class="px-4 py-8 text-center">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">All caught up!</p>
                                    <p class="text-xs text-gray-400">No pending appointments</p>
                                </div>
                                @endforelse
                            </div>

                            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                                <a href="{{ route('admin.appointments.index') }}"
                                   class="block text-sm font-semibold text-center text-blue-600 transition-colors hover:text-blue-800">
                                    View all appointments
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">

                    @if(session('success'))
                    <div class="p-4 mb-6 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg"
                         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-green-600 hover:text-green-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="p-4 mb-6 text-red-800 bg-red-100 border-l-4 border-red-500 rounded-lg"
                         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-600 hover:text-red-800">✕</button>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script>
    function adminLayout() {
        return {
            sidebarOpen: window.innerWidth >= 1024,
            notifOpen: false,
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }
    }
    </script>

    @stack('scripts')
</body>
</html>