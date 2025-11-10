@extends('layouts.app')

@section('title', 'About Us - Tambubong Health Center')

@section('content')
<section class="py-20 bg-white">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h1 class="mb-4 text-4xl font-bold text-gray-900">About Us</h1>
            <p class="text-xl text-gray-600">Learn more about Tambubong Health Center</p>
        </div>

        <div class="grid items-center grid-cols-1 gap-12 mb-16 md:grid-cols-2">
            <div>
                <h2 class="mb-6 text-3xl font-bold text-gray-900">Our Mission</h2>
                <p class="mb-4 text-gray-600">
                    At Tambubong Health Center, we are committed to providing accessible, quality healthcare services to our community. Our mission is to promote wellness, prevent disease, and deliver compassionate care to all residents.
                </p>
                <p class="text-gray-600">
                    We believe that everyone deserves access to excellent healthcare, and we strive to make that a reality through our comprehensive services and dedicated team of healthcare professionals.
                </p>
            </div>
            <div class="flex items-center justify-center h-64 p-8 bg-blue-100 rounded-lg">
                <p class="text-center text-gray-500">Mission Image Placeholder</p>
            </div>
        </div>

        <div class="grid items-center grid-cols-1 gap-12 md:grid-cols-2">
            <div class="flex items-center justify-center order-2 h-64 p-8 bg-green-100 rounded-lg md:order-1">
                <p class="text-center text-gray-500">Vision Image Placeholder</p>
            </div>
            <div class="order-1 md:order-2">
                <h2 class="mb-6 text-3xl font-bold text-gray-900">Our Vision</h2>
                <p class="mb-4 text-gray-600">
                    We envision a healthier community where every individual has the opportunity to achieve optimal health and well-being. Through innovation, collaboration, and excellence in care, we aim to be the leading health center in our region.
                </p>
                <p class="text-gray-600">
                    Our vision includes leveraging technology to improve healthcare delivery, ensuring that our services are efficient, effective, and patient-centered.
                </p>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="mb-8 text-3xl font-bold text-center text-gray-900">Our Values</h2>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="p-6 rounded-lg bg-gray-50">
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Compassion</h3>
                    <p class="text-gray-600">We treat every patient with empathy, respect, and dignity.</p>
                </div>
                <div class="p-6 rounded-lg bg-gray-50">
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Excellence</h3>
                    <p class="text-gray-600">We maintain the highest standards in all aspects of healthcare delivery.</p>
                </div>
                <div class="p-6 rounded-lg bg-gray-50">
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Integrity</h3>
                    <p class="text-gray-600">We uphold honesty and ethical practices in all our interactions.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection