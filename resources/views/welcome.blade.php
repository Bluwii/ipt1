@extends('layouts.app')

@section('title', 'Home - Tambubong Health Center')

@section('content')
<!-- Hero Section - Full Screen -->
<section class="relative flex items-center justify-center min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="absolute inset-0 bg-white/20"></div>
    <div class="container relative z-10 px-4 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-center gap-8 sm:gap-10 lg:flex-row lg:gap-12">
            <!-- Left Content -->
            <div class="text-center lg:w-1/2 lg:text-left">
                <h1 class="mb-4 text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-6xl sm:mb-6">
                    Welcome to
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600">Tambubong Health Center</span>
                </h1>
                
                <p class="mb-6 text-base leading-relaxed text-gray-600 sm:text-lg md:text-xl sm:mb-8">
                    Empowering Community Health with Seamless Appointments and Data-Driven Insights
                </p>
                
                <div class="flex flex-col gap-3 sm:gap-4 sm:flex-row sm:justify-center lg:justify-start">
                    @guest
                        <a href="#services" class="px-8 py-4 text-lg font-semibold text-center text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 rounded-xl hover:scale-105 hover:shadow-xl">
                            Get Started
                        </a>
                        <a href="#about" class="px-6 py-3 text-base font-semibold text-center text-gray-700 transition-colors duration-300 border-2 border-gray-300 rounded-lg sm:px-8 sm:py-4 sm:text-lg hover:border-blue-600 hover:text-blue-600">
                            Learn More
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 text-base font-semibold text-center text-white transition-colors duration-300 bg-blue-600 rounded-lg sm:px-8 sm:py-4 sm:text-lg hover:bg-blue-700">
                            Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
            
            <!-- Right Content - Logo/Image -->
            <div class="flex justify-center w-full lg:w-1/2">
                <div class="relative w-80 h-80 sm:w-96 sm:h-96 md:w-[400px] md:h-[400px]">
                    <!-- Animation Background -->
                    <div class="absolute inset-0 w-full h-full rounded-full bg-gradient-to-r from-blue-400 to-green-500 opacity-20 animate-pulse"></div>
                    <div class="absolute inset-0 rounded-full w-80 h-80 bg-gradient-to-r from-green-400 to-violet-500 opacity-20 animate-pulse animation-delay-1000"></div>
                    
                    <!-- Static White Circle Container -->
                    <div class="absolute flex items-center justify-center bg-white rounded-full shadow-2xl inset-4">
                        <div class="px-4 text-center">
                            <div class="flex items-center justify-center mx-auto mb-6 rounded-full w-28 h-28">
                                <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-full h-full rounded-full">
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Your Health, Our Priority</h3>
                            <p class="mt-2 text-gray-600">Committed to Quality Healthcare</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-12 bg-white sm:py-16 lg:py-20">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-10 text-center sm:mb-12 lg:mb-16">
            <h2 class="mb-3 text-3xl font-bold text-gray-900 sm:text-4xl sm:mb-4">Our Health Services</h2>
            <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg md:text-xl">
                Barangay Tambubong Health Center provides essential healthcare services to the community, ensuring accessible and medical care. The main services offered include:
            </p>
        </div>
        
        <div class="grid grid-cols-1 gap-8 sm:gap-8 md:grid-cols-3">
            <!-- Service 1: Check Up -->
            <div class="flex flex-col h-full overflow-hidden text-center transition-all duration-300 transform border border-gray-200 bg-blue-50 rounded-xl hover:shadow-xl hover:-translate-y-2">
                <!-- Image Container -->
                <div class="w-full">
                    <img src="{{ asset('image/checkup.png') }}" alt="Check Up Services" class="w-full">
                </div>
                
                <!-- Content -->
                <div class="flex flex-col flex-grow p-6">
                    <h3 class="mb-4 text-xl font-bold text-gray-900">CHECK UP</h3>
                    <ul class="space-y-2 text-left text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>General Health Check-up</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Pregnancy Check-up</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Child Growth Monitoring</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Common Illness Consultations</span>
                        </li>
                    </ul>
                </div>
            </div>  
            
            <!-- Service 2: Vaccine -->
            <div class="flex flex-col h-full overflow-hidden text-center transition-all duration-300 transform border border-gray-200 bg-green-50 rounded-xl hover:shadow-xl hover:-translate-y-2">
                <!-- Image Container -->
                <div class="w-full">
                    <img src="{{ asset('image/vaccine.png') }}" alt="Vaccine Services" class="w-full">
                </div>
                
                <!-- Content -->
                <div class="flex flex-col flex-grow p-6">
                    <h3 class="mb-4 text-xl font-bold text-gray-900">VACCINE</h3>
                    <ul class="space-y-2 text-left text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Child Vaccinations</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Adult Vaccinations</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Pregnancy Vaccinations</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Service 3: Request Medicine -->
            <div class="flex flex-col h-full overflow-hidden text-center transition-all duration-300 transform border border-gray-200 bg-purple-50 rounded-xl hover:shadow-xl hover:-translate-y-2">
                <!-- Image Container -->
                <div class="w-full">
                    <img src="{{ asset('image/medecine.png') }}" alt="Medicine Services" class="w-full">
                </div>
                
                <!-- Content -->
                <div class="flex flex-col flex-grow p-6">
                    <h3 class="mb-4 text-xl font-bold text-gray-900">REQUEST MEDICINE</h3>
                    <ul class="space-y-2 text-left text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Medication Assistance</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Free Medication Support</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="contact" class="py-12 bg-blue-600 sm:py-16 lg:py-20">
    <div class="max-w-4xl px-4 mx-auto text-center sm:px-6 lg:px-8">
        <h2 class="mb-4 text-3xl font-bold text-white sm:text-4xl sm:mb-6">Ready to Get Started?</h2>
        <p class="mb-6 text-base text-blue-100 sm:text-lg md:text-xl sm:mb-8">
            Join our community and experience quality healthcare management.
        </p>
        
        @guest
            <div class="flex flex-col justify-center gap-3 sm:gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="px-6 py-3 text-base font-semibold text-blue-600 transition-colors duration-300 bg-white rounded-lg sm:px-8 sm:py-4 sm:text-lg hover:bg-gray-50">
                    Create An Account
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 text-base font-semibold text-white transition-colors duration-300 border-2 border-white rounded-lg sm:px-8 sm:py-4 sm:text-lg hover:bg-white hover:text-blue-600">
                    Sign In
                </a>
            </div>
        @endguest
    </div>
</section>
@endsection