@extends('layouts.app')

@section('title', 'About Our School - Bezaleel')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 md:py-32 min-h-[420px] md:min-h-[520px] bg-slate-900">
        <img src="{{ asset('images/Home-screen1.png') }}" alt="Students at Bezaleel International School"
            class="absolute inset-0 w-full h-full object-cover object-top" />
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/75 via-indigo-900/65 to-purple-900/75"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">About Our School</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Bezaleel International School is a Christian-based academy founded to raise future leaders through
                quality education, godly values, and excellence at every level.
            </p>
        </div>
    </section>

    <!-- About Us Narrative Section -->
    <section class="py-12 sm:py-16 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-8 sm:mb-6 text-center">About Us</h2>
            <div class="space-y-4 sm:space-y-5 text-gray-700 text-base sm:text-lg leading-7 sm:leading-relaxed break-words">
                <p>
                    Bezaleel International School is a Christian-based academy founded in September 2006 on the solid
                    rock of the Lord. As reflected in Scripture (Exodus 35:30-35 and Exodus 36:1), our school was
                    established from a long-standing vision to train, develop, and prepare future leaders of excellence
                    and positive impact.
                </p>
                <p>
                    We welcome pupils and students from all inter-denominational, multicultural backgrounds and
                    nationalities, creating a learning community where every child can grow in knowledge, character,
                    and purpose.
                </p>
                <div>
                    <p class="mb-3">Our educational arms include:</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm sm:text-base">
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel Creche</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel Pre-Nursery</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel Nursery (Reception and Prep)</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel Primary</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel School</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2">Bezaleel Computer School</li>
                        <li class="bg-gray-50 rounded-md px-3 py-2 sm:col-span-2">Bezaleel University</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Mission -->
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        To provide affordable quality education at all levels while shaping students with godly
                        principles, strong character, and lifelong values.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div
                                class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Provide affordable quality education at all levels</p>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Inculcate godly principles and values in every student</p>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mt-1 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Develop excellent, competent, and value-driven individuals</p>
                        </div>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-8 rounded-2xl">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Our Vision</h3>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        To remain a standard and reputable school that upholds progressive academic excellence with
                        distinction through competent and qualified teachers.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Journey</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From humble beginnings to becoming a beacon of educational excellence
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-2xl">2006</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Foundation</h3>
                        <p class="text-gray-600">Founded with a vision to transform education in Nigeria, beginning with a small
                            community of learners and dedicated teachers.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-white font-bold text-2xl">2018</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">BECE Accreditation</h3>
                            <p class="text-gray-600">This was the year we received BECE accreditation, marking a major
                                milestone in our academic growth.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-white font-bold text-2xl">2024</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Excellence</h3>
                            <p class="text-gray-600">Our student enrollment has grown significantly, and we are proudly accredited by WAEC and
                                NECO. With well-equipped facilities, we continue to provide a supportive and enriching learning
                                environment for every student.</p>
                        </div>
                        </div>
                        </div>
                        </section>

                        <!-- Core Values Section -->
                        <section class="py-20 bg-white">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="text-center mb-12">
                                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Core Values</h2>
                                    <p class="text-lg text-gray-600 max-w-4xl mx-auto uppercase">
                                        The following are the core values we live by in this school.
                                    </p>
                                </div>

                                <div class="space-y-6 max-w-5xl mx-auto">
                                    <div class="rounded-xl border border-gray-200 p-5 bg-gray-50">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">(1) CHRIST CENTEREDNESS</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            CHRIST IS OUR FOUNDATION, WE WALK IN FAITH, CONFIDENCE AND GODLY COURAGE.
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 p-5 bg-gray-50">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">(2) DEDICATED SERVICE</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            WE ARE COMMITTED EACH DAY TO MEET THE NEEDS OF OUR PUPILS, STUDENTS, PARENTS AND
                                            SCHOOL COMMUNITY WITH EXCELLENCE.
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 p-5 bg-gray-50">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">(3) EXCELLENCE IN SERVICE</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            WE PURSUE EXCELLENCE IN TEACHING, LEARNING AND SERVICE DELIVERY AT ALL TIMES.
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 p-5 bg-gray-50">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">(4) SAFETY AND STUDENT WELFARE</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            THE SAFETY, WELFARE AND HOLISTIC DEVELOPMENT OF OUR PUPILS AND STUDENTS ARE OUR
                                            UTMOST PRIORITY.
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 p-5 bg-gray-50">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">(5) GODLINESS AND INTEGRITY</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            EVERYTHING WE DO IS GUIDED BY THE FEAR OF GOD, INTEGRITY AND RIGHTEOUSNESS.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Join Our Community</h2>
            <p class="text-xl text-gray-600 mb-8">
                Be part of a school that's shaping the future leaders of tomorrow
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}"
                    class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    Contact Us
                </a>
                <a href="#admissions"
                    class="px-8 py-4 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300 cursor-pointer">
                    Apply Now
                </a>
            </div>
        </div>
    </section>
@endsection
