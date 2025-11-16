@extends('layouts.app')

@section('title', 'About Us - Tambubong Health Center')

@section('content')
<section class="py-8 bg-gray-50 sm:py-12 lg:py-16">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col gap-8 lg:flex-row" x-data="{ sidebarOpen: false }">
            <!-- Mobile/Tablet Sidebar Toggle Button -->
            <button @click="sidebarOpen = true" class="fixed left-0 z-40 p-3 text-white transition-transform bg-blue-600 rounded-r-lg shadow-lg bottom-20 lg:hidden hover:bg-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Overlay for Mobile/Tablet -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
                 style="display: none;">
            </div>

            <!-- Sidebar - Desktop (Always visible) + Mobile/Tablet (Sliding) -->
            <aside class="flex-shrink-0 lg:block lg:w-64">
                <!-- Desktop Sidebar -->
                <div class="hidden lg:block">
                    <div class="sticky space-y-4 top-24">
                        <a href="#mission" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Mission/Vision
                        </a>
                        <a href="#hours" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Opening hours
                        </a>
                        <a href="#location" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Location
                        </a>
                        <a href="#workers" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Barangay Health Workers
                        </a>
                    </div>
                </div>

                <!-- Mobile/Tablet Sliding Sidebar -->
                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-white shadow-xl lg:hidden"
                     style="display: none;">
                    
                    <!-- Close Button -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">About</h3>
                        <button @click="sidebarOpen = false" class="p-2 text-gray-400 rounded-md hover:text-gray-500 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Sidebar Links -->
                    <nav class="p-4 space-y-2">
                        <a href="#mission" @click="sidebarOpen = false" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Mission/Vision
                        </a>
                        <a href="#hours" @click="sidebarOpen = false" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Opening hours
                        </a>
                        <a href="#location" @click="sidebarOpen = false" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Location
                        </a>
                        <a href="#workers" @click="sidebarOpen = false" class="block px-6 py-3 text-base font-medium text-gray-700 transition-colors bg-white rounded-lg shadow-sm hover:bg-blue-50 hover:text-blue-600">
                            Barangay Health Workers
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Hero Section -->
                <div class="flex items-center justify-between mb-8 overflow-hidden bg-[#d6d9da] shadow-lg rounded-2xl h-40">
                    <div class="pl-6 sm:pl-10">
                        <h1 class="text-2xl font-bold leading-tight text-gray-900 sm:text-2xl lg:text-3xl">
                            Welcome to Barangay<br>Tambubong Health Center
                        </h1>
                    </div>
                    <div class="relative flex-shrink-0 w-1/2 h-full">
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent to-[#d6d9da] z-10"></div>
                        <img src="{{ asset('image/hero.png') }}" alt="Health Center Building" class="relative z-0 object-cover w-full h-full rounded-r-2xl">
                    </div>
                </div>

                <!-- Mission/Vision Section -->
                <div id="mission" class="p-8 mb-8 bg-white shadow-lg scroll-mt-24 rounded-2xl sm:p-10">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 sm:text-3xl">Mission/Vision</h2>
                    
                    <div class="space-y-6 text-base leading-relaxed text-gray-700 sm:text-lg">
                        <p>
                            Welcome to Barangay Tambubong Health Center Page, where we combine compassionate care with modern technology to provide exceptional healthcare services. Our mission is to make healthcare accessible, convenient, and transparent for every patient, all while maintaining a commitment to quality and well-being.
                        </p>
                        <p>
                            At Barangay Tambubong Health Center, our vision is to provide healthcare services that are easily accessible, innovative, and patient-focused. We aim to bring comprehensive healthcare closer to you with the integration of online services, transparent data, and an experienced team dedicated to your care.
                        </p>
                    </div>
                </div>

                <!-- Opening Hours Section -->
                <div id="hours" class="p-8 mb-8 bg-white shadow-lg scroll-mt-24 rounded-2xl sm:p-10">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 sm:text-3xl">Opening Hours</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                            <span class="font-medium text-gray-700">Monday - Friday</span>
                            <span class="text-gray-600">8:00 AM - 5:00 PM</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                            <span class="font-medium text-gray-700">Saturday</span>
                            <span class="text-gray-600">8:00 AM - 12:00 PM</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                            <span class="font-medium text-gray-700">Sunday</span>
                            <span class="font-medium text-red-600">Closed</span>
                        </div>
                        <div class="p-4 mt-4 border-l-4 border-blue-500 rounded-lg bg-blue-50">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> For emergencies, please call 0991-275-1509
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div id="location" class="p-8 mb-8 bg-white shadow-lg scroll-mt-24 rounded-2xl sm:p-10">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 sm:text-3xl">Location</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-6 h-6 mt-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Address</p>
                                <p class="text-gray-600">118 Barangay Tambubong Rd, San Rafael, Bulacan, Philippines</p>
                            </div>
                        </div>

                        <!-- Landmarks Section -->
                        <div class="p-4 border-l-4 border-blue-500 rounded-lg bg-blue-50">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Landmarks:</h3>
                            <ul class="space-y-1 text-gray-700 list-disc list-inside">
                                <li>Near Tambubong Elementary School</li>
                                <li>Adjacent to Barangay Hall</li>
                                <li>Accessible via public transportation</li>
                            </ul>
                        </div>
                        
                        <div class="mt-6">
                            <div class="w-full overflow-hidden bg-gray-200 rounded-lg h-72">
                                <!-- Google Maps Embed -->
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241.28318023948822!2d120.9234247!3d14.9676308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397006be0a9e769%3A0x4c771b26a4284f30!2s118%20Barangay%20Tambubong%20Rd%2C%20San%20Rafael%2C%20Bulacan%2C%20Philippines!5e0!3m2!1sen!2sph!4v1710000000000!5m2!1sen!2sph"
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barangay Health Workers Section -->
                <div id="workers" class="p-8 bg-white shadow-lg scroll-mt-24 rounded-2xl sm:p-10">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 sm:text-3xl">Barangay Health Workers</h2>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Health Worker Card 1 -->
                        <div class="p-6 text-center transition-shadow bg-gray-50 rounded-xl hover:shadow-md">
                            <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-blue-100 rounded-full">
                                <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">Imelda Dela Cruz</h3>
                            <p class="text-sm text-gray-600">Team Leader</p>
                            <p class="mt-2 text-sm text-gray-500">0991-275-1509</p>
                        </div>

                        <!-- Health Worker Card 2 -->
                        <div class="p-6 text-center transition-shadow bg-gray-50 rounded-xl hover:shadow-md">
                            <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-green-100 rounded-full">
                                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">Health Worker Name</h3>
                            <p class="text-sm text-gray-600">Midwife</p>
                            <p class="mt-2 text-sm text-gray-500">Contact Info</p>
                        </div>

                        <!-- Health Worker Card 3 -->
                        <div class="p-6 text-center transition-shadow bg-gray-50 rounded-xl hover:shadow-md">
                            <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-purple-100 rounded-full">
                                <svg class="w-12 h-12 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">Health Worker Name</h3>
                            <p class="text-sm text-gray-600">Nurse</p>
                            <p class="mt-2 text-sm text-gray-500">Contact Info</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection