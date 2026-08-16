<!-- Footer Component -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
        
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/bezalee-logo-main.PNG') }}" alt="Bezaleel Logo" class="h-12 w-auto max-w-[180px] object-contain rounded-lg">
                    <span class="text-xl font-bold leading-tight">Bezaleel International School</span>
                </div>
                <p class="text-gray-300 text-sm leading-relaxed">
                    Empowering students with world-class education, fostering academic excellence, and building the
                    leaders of tomorrow.
                </p>
                @php($facebookUrl = config('app.facebook_url'))
                <div class="flex items-center gap-3 pt-1">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer"
                            class="group inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors cursor-pointer">
                    @else
                        <span class="group inline-flex items-center gap-2 text-sm text-gray-400">
                    @endif
                        <span
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-800 text-current group-hover:bg-[#1877F2] group-hover:text-white transition-colors"
                            aria-hidden="true">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium">{{ $facebookUrl ? 'Facebook' : 'Follow us on Facebook' }}</span>
                    @if($facebookUrl)
                        </a>
                    @else
                        </span>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">About Us</a></li>
                    <li><a href="{{ route('academic-calendar.calendar') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Academic
                            Calendar</a></li>
                    <li><a href="{{ route('school-news.index') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">School News</a></li>
                    <li><a href="#admissions"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Admissions</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Contact</a></li>
                </ul>
            </div>

            <!-- Academics -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white">Academics</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('academics') }}" class="text-gray-300 hover:text-white transition-colors cursor-pointer">Academics
                            Overview</a></li>
                    <li><a href="{{ route('resources.syllabus') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Syllabus</a></li>
                    <li><a href="{{ route('resources.timetables') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Timetables</a></li>
                    <li><a href="{{ route('resources.elibrary') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">E-Library</a></li>
                    <li><a href="{{ route('resources.materials') }}"
                            class="text-gray-300 hover:text-white transition-colors cursor-pointer">Study Materials</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white">Contact Info</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-300 text-sm">Berger Quary,Mpape</p>
                            <p class="text-gray-300 text-sm">Abuja, Nigeria</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <div>
                            <p class="text-gray-300 text-sm">+234 8052123760</p>
                            <p class="text-gray-300 text-sm">+234 7014907969</p>
                        
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-gray-300 text-sm">bezaleel996@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="border-t border-gray-800 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    <p>&copy; {{ date('Y') }} Bezaleel. All rights reserved.</p>
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="#privacy" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Privacy
                        Policy</a>
                    <a href="#terms" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Terms of
                        Service</a>
                    <a href="#cookies" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Cookie
                        Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
