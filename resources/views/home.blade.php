@extends('layouts.app')

@section('title', 'Welcome to Our School')

@section('content')
    <!-- Carousel Section (replaces hero) -->
    <section class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 overflow-hidden">
        <div class="w-full">
            <div class="relative  overflow-hidden shadow-lg">
                <!-- Slides -->
                <div id="carousel-slides" class="relative w-full h-[340px] md:h-[480px] lg:h-[540px]">
                    <!-- Slide 1 — no scale on image layer (keeps photo sharp in prod / retina) -->
                    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-90">
                        <div
                            class="w-full h-full relative flex items-center justify-center overflow-hidden text-center px-6">
                            <img src="{{ asset('images/Benz-logo.jpg') }}" alt="Bezaleel school"
                                class="absolute inset-0 z-0 h-full w-full object-cover object-center pointer-events-none"
                                loading="eager" decoding="async">
                            <div class="absolute inset-0 z-[1] bg-black/20" aria-hidden="true"></div>
                            <div class="relative z-10 max-w-3xl text-white">
                                <h2 class="text-4xl md:text-5xl font-bold text-white fade-in-up">Inspiring Excellence Every
                                    Day</h2>
                                <p class="mt-4 text-blue-100 text-lg fade-in-up stagger-1">A caring community where students
                                    grow in knowledge, character, and creativity.</p>
                                <div class="mt-6 fade-in-up stagger-2">
                                    <a href="{{ route('admissions.application') }}"
                                        class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg shadow hover:shadow-md transition cursor-pointer pulse-glow">Apply
                                        for Admission</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0">
                        <div
                            class="w-full h-full relative flex items-center justify-center overflow-hidden text-center px-6">
                            <img src="{{ asset('images/many-students.jpg') }}" alt="Students learning at school"
                                class="absolute inset-0 z-0 h-full w-full object-cover object-center pointer-events-none"
                                loading="lazy" decoding="async">
                            <div class="relative z-10 max-w-3xl">
                                <h2 class="text-4xl md:text-5xl font-bold text-white fade-in-up">Holistic Learning
                                    Environment</h2>
                                <p class="mt-4 text-white text-lg font-bold drop-shadow-lg fade-in-up stagger-1">Balanced
                                    academics, arts, sports, and leadership development for every child.</p>
                                <div class="mt-6 fade-in-up stagger-2">
                                    <a href="{{ route('academics') }}"
                                        class="px-6 py-3 bg-white text-emerald-700 font-semibold rounded-lg shadow hover:shadow-md transition cursor-pointer pulse-glow">Explore
                                        Academics</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 — image layer is not scaled (avoids blur from carousel-breathing) -->
                    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0">
                        <div
                            class="w-full h-full relative flex items-center justify-center text-center px-6 overflow-hidden">
                            <img src="{{ asset('images/invigilator.jpg') }}" alt="Welcoming school community"
                                class="absolute inset-0 z-0 h-full w-full object-cover object-center pointer-events-none"
                                loading="eager" decoding="async">
                            <div class="absolute inset-0 z-[1] bg-gradient-to-t from-black/55 via-black/25 to-black/20"
                                aria-hidden="true"></div>
                            <div class="relative z-10 mx-auto max-w-3xl text-white">
                                <h2 class="text-4xl font-bold text-white drop-shadow-lg fade-in-up md:text-5xl">A Welcoming
                                    School
                                    Community</h2>
                                <p class="mt-4 text-lg text-white/95 drop-shadow-md fade-in-up stagger-1">Partnership with
                                    families and a
                                    supportive staff dedicated to student success.</p>
                                <div class="mt-6 fade-in-up stagger-2">
                                    <a href="{{ route('contact') }}"
                                        class="cursor-pointer rounded-lg bg-white px-6 py-3 font-semibold text-indigo-700 shadow transition hover:shadow-md pulse-glow">Contact
                                        Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
                    <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/60 hover:bg-white transition"></button>
                    <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition"></button>
                    <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Foundation of Learning Section -->


    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 gradient-text fade-in-up">What Makes Our School Special
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto fade-in-up stagger-1">
                    An inspiring environment, dedicated educators, and a holistic approach to learning
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Holistic Curriculum -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-1">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Holistic Curriculum</h3>
                    <p class="text-gray-600 leading-relaxed">
                        A balanced curriculum that nurtures academics, arts, sports, leadership, and life skills.
                    </p>
                </div>

                <!-- Academic Excellence -->
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-2">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Academic Excellence</h3>
                    <p class="text-gray-600 leading-relaxed">
                        High expectations, passionate teachers, and personalized support to help every learner thrive.
                    </p>
                </div>

                <!-- Parent Partnership -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-3">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Parent Partnership</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Strong home–school relationships that keep families informed and engaged in learning.
                    </p>
                </div>

                <!-- Clubs & Activities -->
                <div
                    class="bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-4">
                    <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Clubs & Co‑curricular</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Vibrant clubs, societies, and sports programs that build confidence and teamwork.
                    </p>
                </div>

                <!-- Safe & Secure -->
                <div
                    class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-5">
                    <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Safe & Secure Environment</h3>
                    <p class="text-gray-600 leading-relaxed">
                        A caring, safe environment where every student is valued and protected.
                    </p>
                </div>

                <!-- Modern Facilities -->
                <div
                    class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover-lift scale-in stagger-6">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6 float">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Modern Facilities & ICT</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Well‑equipped classrooms, labs, and digital tools that enhance teaching and learning.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <!-- Text Content -->
                <div class="flex-1 lg:pr-8">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6 gradient-text fade-in-left">Foundation of Learning</h2>
                    <p class="text-xl text-gray-600 leading-relaxed mb-6 fade-in-left stagger-1">
                        Bezaleel International School believe that every one of our Pupils and students have the ability to
                        excel.
                        We also recognize that the path to excellence will be different for each of us, which is why we are
                        highly adaptive in our approach to education.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 fade-in-left stagger-2">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Personalized learning approaches tailored to individual strengths</p>
                        </div>
                        <div class="flex items-start space-x-3 fade-in-left stagger-3">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Adaptive teaching methods that accommodate diverse learning styles</p>
                        </div>
                        <div class="flex items-start space-x-3 fade-in-left stagger-4">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Continuous assessment and feedback to guide student growth</p>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="flex-1">
                    <div class="shadow-lg fade-in-right hover-lift overflow-hidden rounded-lg"
                        style="width: 100%; height: 450px;">
                        <img src="/images/Home-screen2.png" alt="Students learning together"
                            style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Student Excellence Section -->
    <section class="py-20 bg-gradient-to-br from-indigo-50/40 via-purple-50/40 to-pink-50/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <!-- Image -->
                <div class="flex-1 w-full md:order-1">
                    <div class="shadow-lg fade-in-left hover-lift overflow-hidden rounded-lg"
                        style="width: 100%; height: 450px;">
                        <img src="{{ asset('images/IMG-20260424-WA0079.jpg') }}" alt="Exemplary Bezaleel student"
                            style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    </div>
                </div>

                <!-- Text Content -->
                <div class="flex-1 lg:pl-8 md:order-2">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6 gradient-text fade-in-right">Culture of Excellence</h2>
                    <p class="text-xl text-gray-600 leading-relaxed mb-6 fade-in-right stagger-1">
                        At Bezaleel International School, academic excellence is not just a goal, but a way of life. 
                        Our pupils and students consistently achieve top honors in regional and national competitions, 
                        demonstrating outstanding performance, creativity, and deep commitment to learning.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 fade-in-right stagger-2">
                            <div class="w-2 h-2 bg-indigo-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Exceptional performance in national examinations and academic contests</p>
                        </div>
                        <div class="flex items-start space-x-3 fade-in-right stagger-3">
                            <div class="w-2 h-2 bg-indigo-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Fostering strong leadership skills and outstanding moral character</p>
                        </div>
                        <div class="flex items-start space-x-3 fade-in-right stagger-4">
                            <div class="w-2 h-2 bg-indigo-600 rounded-full mt-3 pulse-glow"></div>
                            <p class="text-gray-600">Empowering critical thinking and real-world problem-solving abilities</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Statistics Section -->


    <!-- Role-Based Highlights -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">For Every Member of Our Community</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Together we inspire a love of learning and a spirit of service
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Students -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Students</h3>
                    <p class="text-gray-600 text-sm">Engaging lessons, supportive mentoring, and opportunities to excel in
                        every field.</p>
                </div>

                <!-- Parents -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Parents</h3>
                    <p class="text-gray-600 text-sm">Open communication, progress updates, and a welcoming school–home
                        partnership.</p>
                </div>

                <!-- Teachers -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Teachers</h3>
                    <p class="text-gray-600 text-sm">Professional, caring educators dedicated to inspiring curiosity and
                        growth.</p>
                </div>

                <!-- Administration -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Administration</h3>
                    <p class="text-gray-600 text-sm">A dedicated leadership team focused on student success and well‑being.
                    </p>
                </div>
            </div>
        </div>
    </section>



    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Discover the Difference</h2>
            <p class="text-xl text-blue-100 mb-8">
                Book a visit or reach out to our admissions team to learn more about enrolling
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}"
                    class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    Contact Admissions
                </a>
                <a href="#calendar"
                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 cursor-pointer">
                    View School Calendar
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        </section>



        @push('styles')
            <style>
                .fade-in-up {
                    opacity: 0;
                    transform: translateY(30px);
                    transition: all 0.8s ease-out;
                }

                .fade-in-up.animate {
                    opacity: 1;
                    transform: translateY(0);
                }

                .fade-in-left {
                    opacity: 0;
                    transform: translateX(-30px);
                    transition: all 0.8s ease-out;
                }

                .fade-in-left.animate {
                    opacity: 1;
                    transform: translateX(0);
                }

                .fade-in-right {
                    opacity: 0;
                    transform: translateX(30px);
                    transition: all 0.8s ease-out;
                }

                .fade-in-right.animate {
                    opacity: 1;
                    transform: translateX(0);
                }

                .scale-in {
                    opacity: 0;
                    transform: scale(0.8);
                    transition: all 0.8s ease-out;
                }

                .scale-in.animate {
                    opacity: 1;
                    transform: scale(1);
                }

                /* Floating animation for icons */
                .float {
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-10px);
                    }
                }

                /* Pulse animation for buttons */
                .pulse-glow {
                    animation: pulse-glow 2s ease-in-out infinite;
                }

                @keyframes pulse-glow {

                    0%,
                    100% {
                        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
                    }

                    50% {
                        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
                    }
                }

                /* Gradient text animation */
                .gradient-text {
                    background: linear-gradient(-45deg, #3b82f6, #8b5cf6, #06b6d4, #10b981);
                    background-size: 400% 400%;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    animation: gradient-shift 4s ease infinite;
                }

                @keyframes gradient-shift {
                    0% {
                        background-position: 0% 50%;
                    }

                    50% {
                        background-position: 100% 50%;
                    }

                    100% {
                        background-position: 0% 50%;
                    }
                }

                /* Staggered animation delays */
                .stagger-1 {
                    transition-delay: 0.1s;
                }

                .stagger-2 {
                    transition-delay: 0.2s;
                }

                .stagger-3 {
                    transition-delay: 0.3s;
                }

                .stagger-4 {
                    transition-delay: 0.4s;
                }

                .stagger-5 {
                    transition-delay: 0.5s;
                }

                .stagger-6 {
                    transition-delay: 0.6s;
                }

                /* Enhanced hover effects */
                .hover-lift {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                .hover-lift:hover {
                    transform: translateY(-8px) scale(1.02);
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                }

                /* Shimmer effect */
                .shimmer {
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
                    background-size: 200% 100%;
                    animation: shimmer 2s infinite;
                }

                @keyframes shimmer {
                    0% {
                        background-position: -200% 0;
                    }

                    100% {
                        background-position: 200% 0;
                    }
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const slides = Array.from(document.querySelectorAll('.carousel-slide'));
                    const dots = Array.from(document.querySelectorAll('.carousel-dot'));
                    const prev = document.getElementById('carousel-prev');
                    const next = document.getElementById('carousel-next');
                    let index = 0;
                    let timer = null;

                    // Check if elements exist before proceeding
                    if (!slides.length || !dots.length) {
                        console.warn('Carousel elements not found');
                        return;
                    }

                    function show(i) {
                        slides.forEach((s, idx) => {
                            s.style.opacity = idx === i ? '1' : '0';
                            s.style.pointerEvents = idx === i ? 'auto' : 'none';
                        });
                        dots.forEach((d, idx) => {
                            d.style.backgroundColor = idx === i ? 'rgba(255,255,255,0.95)' : 'rgba(255,255,255,0.5)';
                            d.style.width = idx === i ? '12px' : '10px';
                            d.style.height = idx === i ? '12px' : '10px';
                        });
                        index = i;
                    }

                    function nextSlide() { show((index + 1) % slides.length); }
                    function prevSlide() { show((index - 1 + slides.length) % slides.length); }

                    function startAuto() {
                        stopAuto();
                        timer = setInterval(nextSlide, 6000);
                    }
                    function stopAuto() { if (timer) clearInterval(timer); }

                    // Add event listeners only if elements exist
                    if (next) {
                        next.addEventListener('click', () => { nextSlide(); startAuto(); });
                    }
                    if (prev) {
                        prev.addEventListener('click', () => { prevSlide(); startAuto(); });
                    }

                    dots.forEach((d, i) => d.addEventListener('click', () => { show(i); startAuto(); }));

                    const container = document.getElementById('carousel-slides');
                    if (container) {
                        container.addEventListener('mouseenter', stopAuto);
                        container.addEventListener('mouseleave', startAuto);
                    }

                    // Initialize carousel
                    show(0);
                    startAuto();

                    // Scroll animations
                    const observerOptions = {
                        threshold: 0.1,
                        rootMargin: '0px 0px -50px 0px'
                    };

                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animate');
                            }
                        });
                    }, observerOptions);

                    // Observe all animated elements
                    document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in').forEach(el => {
                        observer.observe(el);
                    });

                });
            </script>
        @endpush
@endsection
