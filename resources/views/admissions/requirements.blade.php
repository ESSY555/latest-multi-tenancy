@extends('layouts.app')

@section('title', 'Admission Requirements - Bezaleel')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="w-20 h-20 bg-gradient-to-r from-green-600 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Admission Requirements</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Everything you need to know about documents and eligibility for enrollment
            </p>
        </div>
    </section>

    <!-- Requirements Overview -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">What You'll Need</h2>
                <p class="text-lg text-gray-600">Prepare these documents to ensure a smooth application process</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Required Documents -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Required Documents
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start requirement-item">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-gray-700">Birth certificate or passport</span>
                        </li>
                    <li class="flex items-start requirement-item">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-gray-700">Previous school records</span>
                    </li>
                    <li class="flex items-start requirement-item">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-gray-700">Parent/Guardian ID</span>
                    </li>

                    <li class="flex items-start requirement-item">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-gray-700">Recent passport-sized photographs (2)</span>
                    </li>
                    <li class="flex items-start requirement-item">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-gray-700">Medical history form</span>
                    </li>
                    </ul>
                    </div>

                    <!-- Age Requirements -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Age Requirements
                        </h3>
                        <div class="space-y-4">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-900">Pre-Nursary</h4>
                                <p class="text-gray-600">2-3 years old by September 1st</p>
                                <p class="text-sm text-gray-500">Must be potty trained</p>
                            </div>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-gray-900">Nursary</h4>
                                <p class="text-gray-600">3-5 years old by September 1st</p>
                                <p class="text-sm text-gray-500">Basic readiness skills required</p>
                            </div>
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-semibold text-gray-900">Junior Class</h4>
                                <p class="text-gray-600">10-13 years old</p>
                                <p class="text-sm text-gray-500">Previous school records needed</p>
                            </div>
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="font-semibold text-gray-900">Senior Class</h4>
                                <p class="text-gray-600">13-16 years old</p>
                                <p class="text-sm text-gray-500">Academic assessment required</p>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </section>

                    <!-- Additional Requirements -->
                    <section class="py-20 bg-gray-50">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center mb-16">
                                <h2 class="text-3xl font-bold text-gray-900 mb-4">Additional Information</h2>
                                <p class="text-lg text-gray-600">Other factors we consider during the admission process</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Academic Readiness -->
                                <div class="bg-white p-6 rounded-xl shadow-lg">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Academic Readiness</h3>
                                    <p class="text-gray-600 text-center text-sm">We assess each child's readiness for their grade level to
                                        ensure they can thrive academically.</p>
                                </div>

                                <!-- Social Development -->
                                <div class="bg-white p-6 rounded-xl shadow-lg">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Social Development</h3>
                                    <p class="text-gray-600 text-center text-sm">We look for children who can interact positively with peers
                                        and follow classroom guidelines.</p>
                                </div>

                                <!-- Family Commitment -->
                                <div class="bg-white p-6 rounded-xl shadow-lg">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Family Commitment</h3>
                                    <p class="text-gray-600 text-center text-sm">We seek families who are committed to supporting their
                                        child's education and our school values.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Document Preparation Tips -->
                    <section class="py-20 bg-white">
                        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center mb-16">
                                <h2 class="text-3xl font-bold text-gray-900 mb-4">Document Preparation Tips</h2>
                                <p class="text-lg text-gray-600">Make your application process smoother with these helpful tips</p>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-blue-50 p-6 rounded-xl border-l-4 border-blue-500">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Start Early</h3>
                                    <p class="text-gray-700">Begin gathering documents at least 2-3 weeks before you plan to submit your
                                        application. Some documents may take time to obtain.</p>
                                </div>

                                <div class="bg-green-50 p-6 rounded-xl border-l-4 border-green-500">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Make Copies</h3>
                                    <p class="text-gray-700">Keep original documents safe and submit clear, legible copies. We recommend
                                        scanning documents for the best quality.</p>
                                </div>

                                <div class="bg-purple-50 p-6 rounded-xl border-l-4 border-purple-500">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Check Expiry Dates</h3>
                                    <p class="text-gray-700">Ensure all documents are current and not expired.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-teal-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Apply?</h2>
            <p class="text-xl text-green-100 mb-8">
                Now that you know what's required, let's start your application
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admissions.application') }}"
                    class="px-8 py-4 bg-white text-green-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    Start Application
                </a>
                <a href="{{ route('admissions.process') }}"
                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-green-600 transition-all duration-300 cursor-pointer">
                    View Process
                </a>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .requirement-item {
                transition: all 0.2s ease;
            }

            .requirement-item:hover {
                transform: translateX(5px);
                color: #1f2937;
            }
        </style>
    @endpush
@endsection
