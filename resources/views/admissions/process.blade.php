@extends('layouts.app')

@section('title', 'Admission Process - Bezaleel')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Admission Process</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Simple steps to enroll your child in our school and join our learning community
            </p>
        </div>
    </section>

    <!-- Process Steps -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How to Apply</h2>
                <p class="text-lg text-gray-600">Follow these four simple steps to begin your child's educational journey
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 process-step border border-gray-100">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-white font-bold text-2xl">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Inquiry & Visit</h3>
                    <p class="text-gray-600 text-center mb-6">Contact us to schedule a school visit and learn about our
                        programs</p>
                    <div class="text-center">
                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <span>Schedule Visit</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 process-step border border-gray-100">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-white font-bold text-2xl">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Application</h3>
                    <p class="text-gray-600 text-center mb-6">Complete the application form and submit required documents
                    </p>
                    <div class="text-center">
                        <a href="{{ route('admissions.application') }}"
                            class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                            <span>Apply Now</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 process-step border border-gray-100">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-white font-bold text-2xl">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Assessment</h3>
                    <p class="text-gray-600 text-center mb-6">Student assessment and family interview to ensure the best fit
                    </p>
                    <div class="text-center">
                        <span class="text-purple-600 font-medium">We'll Contact You</span>
                    </div>
                </div>

                <!-- Step 4 -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 process-step border border-gray-100">
                    <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="text-white font-bold text-2xl">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Enrollment</h3>
                    <p class="text-gray-600 text-center mb-6">Complete enrollment and welcome to our school family!</p>
                    <div class="text-center">
                        <span class="text-orange-600 font-medium">Welcome!</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Application Timeline</h2>
                <p class="text-lg text-gray-600">What to expect during the admission process</p>
            </div>

            <div class="space-y-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Initial Contact</h3>
                        <p class="text-gray-600">Within 24 hours of your inquiry, our admissions team will respond with
                            information and schedule options.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">School Visit</h3>
                        <p class="text-gray-600">Schedule a personalized tour of our campus and meet with our staff to learn
                            about our programs.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Application Review</h3>
                        <p class="text-gray-600">Our team reviews your application and documents within 3-5 business days.
                        </p>
                    </div>
                </div>


                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Decision & Enrollment</h3>
                        <p class="text-gray-600">Receive admission decision within 1 week and complete enrollment paperwork.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Begin your child's educational journey with us today
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admissions.application') }}"
                    class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    Start Application
                </a>
                <a href="{{ route('admissions.requirements') }}"
                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 cursor-pointer">
                    View Requirements
                </a>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .process-step {
                transition: all 0.3s ease;
            }

            .process-step:hover {
                transform: translateY(-5px) scale(1.02);
            }
        </style>
    @endpush
@endsection
