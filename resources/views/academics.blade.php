@extends('layouts.app')

@section('title', 'Explore Academics - Bezaleel International School')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 to-teal-600 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">Explore Academics</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto leading-relaxed">
                    A comprehensive curriculum designed to inspire curiosity, foster critical thinking, and prepare students
                    for success in an ever-changing world.
                </p>
            </div>
        </div>
    </section>

    <!-- Academic Philosophy Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Academic Philosophy</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        At Bezaleel, we believe in nurturing the whole child through a balanced approach that combines
                        academic rigor with creative expression, character development, and real-world application.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-emerald-600 rounded-full mt-3"></div>
                            <p class="text-gray-600">Student-centered learning that adapts to individual needs</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-emerald-600 rounded-full mt-3"></div>
                            <p class="text-gray-600">Project-based learning that connects theory to practice</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-emerald-600 rounded-full mt-3"></div>
                            <p class="text-gray-600">Technology integration for 21st-century skills</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-emerald-600 rounded-full mt-3"></div>
                            <p class="text-gray-600">Character education and social-emotional learning</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-8 rounded-2xl">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Learning for Life</h3>
                        <p class="text-gray-600">
                            We prepare students not just for exams, but for life's challenges and opportunities.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Subjects Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Core Academic Subjects</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    A comprehensive curriculum that builds strong foundations in essential subjects
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Mathematics -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mathematics</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Building strong mathematical foundations through problem-solving, critical thinking, and real-world
                        applications.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Number sense and operations</li>
                        <li>• Algebra and geometry</li>
                        <li>• Data analysis and probability</li>
                        <li>• Mathematical reasoning</li>
                    </ul>
                </div>

                <!-- English Language Arts -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">English Language Arts</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Developing literacy skills through reading, writing, speaking, and listening across diverse genres
                        and contexts.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Reading comprehension</li>
                        <li>• Creative and academic writing</li>
                        <li>• Grammar and vocabulary</li>
                        <li>• Public speaking and presentation</li>
                    </ul>
                </div>

                <!-- Science -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Science</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Exploring the natural world through inquiry-based learning, experimentation, and scientific
                        reasoning.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Life sciences and biology</li>
                        <li>• Physical sciences and chemistry</li>
                        <li>• Earth and space sciences</li>
                        <li>• Scientific method and inquiry</li>
                    </ul>
                </div>

                <!-- Social Studies -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Social Studies</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Understanding human societies, cultures, history, and geography to become informed global citizens.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• World history and civilizations</li>
                        <li>• Geography and cultural studies</li>
                        <li>• Civics and government</li>
                        <li>• Economics and current events</li>
                    </ul>
                </div>

                <!-- Technology & ICT -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Technology & ICT</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Developing digital literacy and computational thinking skills for the modern world.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Computer science fundamentals</li>
                        <li>• Digital tools and applications</li>
                        <li>• Coding and programming</li>
                        <li>• Digital citizenship and safety</li>
                    </ul>
                </div>

                <!-- Arts & Creative Expression -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-pink-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Arts & Creative Expression</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Fostering creativity, self-expression, and appreciation for the arts in all their forms.
                    </p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Visual arts and design</li>
                        <li>• Music and performing arts</li>
                        <li>• Drama and theater</li>
                        <li>• Creative writing and poetry</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Approach Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Learning Approach</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Innovative teaching methods that engage students and promote deep learning
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Project-Based Learning -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Project-Based Learning</h3>
                    <p class="text-gray-600">
                        Students work on real-world projects that integrate multiple subjects and develop critical thinking
                        skills.
                    </p>
                </div>

                <!-- Differentiated Instruction -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Differentiated Instruction</h3>
                    <p class="text-gray-600">
                        Tailored teaching approaches that meet each student's unique learning needs and preferences.
                    </p>
                </div>

                <!-- Technology Integration -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Technology Integration</h3>
                    <p class="text-gray-600">
                        Seamless use of digital tools to enhance learning and prepare students for the digital age.
                    </p>
                </div>

                <!-- Collaborative Learning -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Collaborative Learning</h3>
                    <p class="text-gray-600">
                        Group activities and peer learning that develop teamwork, communication, and social skills.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment & Progress Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Assessment & Progress</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        We use a comprehensive assessment system that provides meaningful feedback and supports continuous
                        improvement.
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Continuous Assessment</h3>
                                <p class="text-gray-600">Regular evaluations that track progress and provide timely feedback
                                    for improvement.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Portfolio Assessment</h3>
                                <p class="text-gray-600">Student portfolios showcasing growth, achievements, and learning
                                    journey over time.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Parent-Teacher Conferences</h3>
                                <p class="text-gray-600">Regular meetings to discuss student progress and collaborate on
                                    learning goals.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Academic Excellence</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Student Achievement</span>
                                <span class="font-bold text-emerald-600">95%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 95%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">College Readiness</span>
                                <span class="font-bold text-blue-600">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 92%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Parent Satisfaction</span>
                                <span class="font-bold text-purple-600">98%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-emerald-600 to-teal-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Join Our Academic Community?</h2>
            <p class="text-xl text-emerald-100 mb-8">
                Discover how our comprehensive academic program can nurture your child's potential
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admissions.application') }}"
                    class="px-8 py-4 bg-white text-emerald-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    Apply Now
                </a>
                <a href="{{ route('contact') }}"
                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-emerald-600 transition-all duration-300 cursor-pointer">
                    Schedule a Visit
                </a>
            </div>
        </div>
    </section>

@endsection
