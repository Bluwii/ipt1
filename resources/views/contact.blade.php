@extends('layouts.app')

@section('title', 'Contact Us - Tambubong Health Center')

@section('content')
<!-- Contact Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-16 text-center">
            <h1 class="mb-4 text-4xl font-bold md:text-4xl">Contact Us</h1>
             <p class="max-w-xl mx-auto text-lg text-gray-600 ">
        Connect with Tambubong Health Center today. Call, message, or visit our Facebook page 
        for all your healthcare needs and community health services.
    </p>
        </div>

        <!-- Contact Cards -->
        <div class="grid max-w-4xl grid-cols-1 gap-8 mx-auto md:grid-cols-2">
            <!-- Phone Contact Card -->
            <div class="p-8 text-center transition-shadow duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl">
                <div class="flex justify-center mb-6">
                    <div class="flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full">
                        <svg class="w-12 h-12 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27 11.36 11.36 0 004.48.9 1 1 0 011 1v4a1 1 0 01-1 1A18 18 0 013 4a1 1 0 011-1h4a1 1 0 011 1 11.36 11.36 0 00.9 4.48 1 1 0 01-.27 1.11l-2.2 2.2z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mb-4 text-2xl font-bold text-gray-900">0991-275-1509</h2>
                <h3 class="mb-2 text-xl font-semibold text-gray-800 underline">Imelda Dela Cruz</h3>
                <p class="text-gray-600">Barangay Health Worker</p>
                <p class="text-sm italic text-gray-500">Leader</p>
            </div>

            <!-- Facebook Contact Card -->
            <div class="p-8 text-center transition-shadow duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl">
                <div class="flex justify-center mb-6">
                    <div class="flex items-center justify-center w-24 h-24 bg-blue-500 rounded-full">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mb-4 text-2xl font-bold text-gray-900">Tambubong San Rafael Bulacan</h2>
                <p class="mb-4 text-gray-600">Official Facebook Page</p>
                <div class="flex justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <a href="https://web.facebook.com/SangguniangBarangayNgTambubong" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-block text-blue-600 break-all hover:text-blue-800 hover:underline">
                    https://www.facebook.com/share/1HxZbyjPtx/?mibextid=wwXIfr
                </a>
            </div>
        </div>
    </div>
</section>
@endsection