@extends('layouts.app')

@section('title', 'Home - Tambubong Health Center')

@section('content')
<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-12 lg:flex-row">
            <!-- Left Content -->
            <div class="lg:w-1/2">
                <h1 class="mb-6 text-5xl font-bold leading-tight text-gray-900 lg:text-6xl">
                    Welcome to
                    <span class="text-blue-600">Tambubong Health Center</span>
                </h1>
                
                <p class="mb-8 text-xl leading-relaxed text-gray-600">
                    Empowering Community Health with Seamless Appointments and Data-Driven Insights
                </p>
                
                <div class="flex flex-col gap-4 sm:flex-row">
                    @guest
                        <a href="#" class="px-8 py-4 text-lg font-semibold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Get Started
                        </a>
                        <a href="#" class="px-8 py-4 text-lg font-semibold text-center text-gray-700 border-2 border-gray-300 rounded-lg hover:border-blue-600 hover:text-blue-600">
                            Learn More
                        </a>
                    @else
                        @if(auth()->user()->isAdmin())
                            <a href="#" class="px-8 py-4 text-lg font-semibold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Go to Admin Dashboard
                            </a>
                        @else
                            <a href="#" class="px-8 py-4 text-lg font-semibold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Go to Dashboard
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
            
            <!-- Right Content - Logo/Image -->
            <div class="flex justify-center lg:w-1/2">
                <div class="relative flex items-center justify-center bg-white rounded-full shadow-xl w-80 h-80">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto mb-4">
                            <img src="{{ asset('image/logo.png') }}" alt="Tambubong Health Center Logo" class="object-contain w-full h-full">
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Your Health, Our Priority</h3>
                        <p class="mt-2 text-gray-600">Committed to Quality Healthcare</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">Why Choose Our Platform?</h2>
            <p class="max-w-3xl mx-auto text-xl text-gray-600">
                Discover the features that make our health center management system efficient and reliable.
            </p>
        </div>
        
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <!-- Feature 1 -->
            <div class="p-8 text-center transition-shadow rounded-xl bg-blue-50 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-blue-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-900">Secure Authentication</h3>
                <p class="text-gray-600">
                    Advanced security features with role-based access control and encrypted data storage.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="p-8 text-center transition-shadow rounded-xl bg-purple-50 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-purple-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-900">Easy Appointments</h3>
                <p class="text-gray-600">
                    Schedule and manage appointments effortlessly with our intuitive booking system.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="p-8 text-center transition-shadow rounded-xl bg-green-50 hover:shadow-lg">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-green-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-900">Health Records</h3>
                <p class="text-gray-600">
                    Comprehensive health record management with secure access and easy retrieval.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-blue-600">
    <div class="max-w-4xl px-4 mx-auto text-center sm:px-6 lg:px-8">
        <h2 class="mb-6 text-4xl font-bold text-white">Ready to Get Started?</h2>
        <p class="mb-8 text-xl text-blue-100">
            Join our community and experience quality healthcare management.
        </p>
        
        @guest
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-semibold text-blue-600 bg-white rounded-lg hover:bg-gray-50">
                    Create Account
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-lg hover:bg-white hover:text-blue-600">
                    Sign In
                </a>
            </div>
        @endguest
    </div>
</section>
@endsection