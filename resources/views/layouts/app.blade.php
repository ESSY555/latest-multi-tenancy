<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('head')
</head>

<body class="@yield('body_class')">
    <div class="fixed top-4 right-4 z-[70] w-full max-w-md px-4 pointer-events-none">
        <div class="pointer-events-auto">
            <x-flash-alerts />
        </div>
    </div>
    <!-- Navigation -->
    <header class="w-full bg-white border-b shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Main Navigation -->
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/bezalee-logo-main.PNG') }}" alt=""
                            class="h-12 w-auto max-w-[180px] object-contain shrink-0 rounded-md" />
                        <span class="hidden sm:inline text-xl font-bold text-gray-900">Bezaleel International School</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('about') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors font-medium">About</a>
                    <a href="{{ route('academic-calendar.calendar') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Calendar</a>
                    <a href="{{ route('school-news.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors font-medium">School News</a>



                    <!-- Admissions Dropdown -->
                    <div class="relative group">
                        <button
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium flex items-center">
                            Admissions
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-2">
                                <a href="{{ route('admissions.process') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Admission
                                    Process</a>
                                <a href="{{ route('admissions.requirements') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Requirements</a>
                                <a href="{{ route('admissions.application') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Application
                                    Forms</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Contact Us</a>

                

                    <!-- Resources Dropdown -->
                    <div class="relative group">
                        <button
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium flex items-center">
                            Resources
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-2">
                                <a href="{{ route('resources.syllabus') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Syllabus</a>
                                <a href="{{ route('resources.timetables') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Exam
                                    Timetables</a>
                                <a href="{{ route('resources.elibrary') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">E-Library</a>
                                <a href="{{ route('resources.materials') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Study
                                    Materials</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('gallery.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Gallery</a>
                </nav>

                <!-- Right Side Actions -->
                <div class="hidden md:flex items-center space-x-4">


                    @auth
                        @php
                            $hideDashboardButton = request()->routeIs('dashboard', 'student.dashboard', 'parent.dashboard', 'form-teacher.dashboard');
                        @endphp
                        @php
    $recentNotifications = \App\Models\Notification::forUser(auth()->id())
        ->latest()
        ->limit(12)
        ->get();
    $unreadNotificationCount = \App\Models\Notification::forUser(auth()->id())
        ->unread()
        ->count();
                        @endphp
                        <div class="relative" id="notification-menu">
                            <button type="button" id="notification-toggle" class="relative px-3 py-2 text-gray-700 hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50">
                                <i class="fas fa-bell text-lg"></i>
                                @if($unreadNotificationCount > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-600 rounded-full">
                                        {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                    </span>
                                @endif
                            </button>
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-[92vw] max-w-sm sm:w-96 bg-white rounded-2xl shadow-2xl z-50 border border-gray-100 overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-gray-900">Notifications</p>
                                    @if($unreadNotificationCount > 0)
                                        <form method="POST" action="{{ route('notifications.read-all') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-[65vh] overflow-y-auto divide-y divide-gray-50">
                                    @forelse($recentNotifications as $notification)
                                        @php
        $targetUrl = route('dashboard');
        $data = is_array($notification->data) ? $notification->data : [];
        $typeLabel = 'System';
        $typeIcon = 'fa-bell';
        $typeClasses = 'bg-gray-100 text-gray-600';

        switch ($notification->type) {
            case \App\Models\Notification::TYPE_ASSIGNMENT_PUBLISH:
            case \App\Models\Notification::TYPE_ASSIGNMENT_REVIEW:
                $typeLabel = 'Assignment';
                $typeIcon = 'fa-book-open';
                $typeClasses = 'bg-indigo-100 text-indigo-700';
                $targetUrl = route('student.assignments');
                break;
            case \App\Models\Notification::TYPE_ASSIGNMENT_SUBMISSION:
                $typeLabel = 'Submission';
                $typeIcon = 'fa-file-arrow-up';
                $typeClasses = 'bg-blue-100 text-blue-700';
                $targetUrl = isset($data['assignment_id'])
                    ? route('assignments.show', $data['assignment_id'])
                    : route('assignments.index');
                break;
            case \App\Models\Notification::TYPE_LESSON_PLAN:
                $typeLabel = 'Lesson Plan';
                $typeIcon = 'fa-clipboard-list';
                $typeClasses = 'bg-purple-100 text-purple-700';
                $targetUrl = isset($data['lesson_plan_id'])
                    ? route('lesson-plans.show', $data['lesson_plan_id'])
                    : route('lesson-plans.index');
                break;
            case \App\Models\Notification::TYPE_TEACHER_REPORT:
                $typeLabel = 'Teacher Report';
                $typeIcon = 'fa-file-lines';
                $typeClasses = 'bg-emerald-100 text-emerald-700';
                $targetUrl = isset($data['teacher_report_id'])
                    ? route('teacher-reports.show', $data['teacher_report_id'])
                    : route('teacher-reports.index');
                break;
            case \App\Models\Notification::TYPE_RESULT:
                $typeLabel = 'Result';
                $typeIcon = 'fa-square-poll-vertical';
                $typeClasses = 'bg-green-100 text-green-700';
                $targetUrl = route('dashboard');
                break;
            case \App\Models\Notification::TYPE_ATTENDANCE:
                $typeLabel = 'Attendance';
                $typeIcon = 'fa-calendar-check';
                $typeClasses = 'bg-orange-100 text-orange-700';
                $targetUrl = route('dashboard');
                break;
            case \App\Models\Notification::TYPE_ANNOUNCEMENT:
                $typeLabel = 'Announcement';
                $typeIcon = 'fa-bullhorn';
                $typeClasses = 'bg-pink-100 text-pink-700';
                $targetUrl = route('dashboard');
                break;
            case \App\Models\Notification::TYPE_EXAM_TIMETABLE:
                $typeLabel = 'Exam Timetable';
                $typeIcon = 'fa-calendar-days';
                $typeClasses = 'bg-cyan-100 text-cyan-700';
                $targetUrl = route('exam-timetables.view');
                break;
            default:
                $targetUrl = route('dashboard');
                break;
        }
                                        @endphp
                                        <div class="px-4 py-3 {{ !$notification->is_read ? 'bg-blue-50/40' : 'bg-white' }}">
                                            <a href="{{ route('notifications.open', ['notification' => $notification->id, 'redirect' => $targetUrl]) }}" class="block hover:bg-gray-50 rounded-lg p-1 -m-1 transition-colors">
                                                <div class="flex items-start gap-2">
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full {{ $typeClasses }}">
                                                        <i class="fas {{ $typeIcon }} text-xs"></i>
                                                    </span>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <p class="text-sm font-semibold text-gray-900 leading-snug truncate">{{ $notification->title }}</p>
                                                            <span class="hidden sm:inline-flex text-[10px] px-1.5 py-0.5 rounded-full {{ $typeClasses }} font-semibold">{{ $typeLabel }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $notification->message }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="mt-2 flex items-center justify-between">
                                                <p class="text-[11px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                                <div class="flex items-center gap-2">
                                                    @if(!$notification->is_read)
                                                        <span class="text-[10px] font-semibold text-blue-600 uppercase tracking-wide">New</span>
                                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                                            @csrf
                                                            <button type="submit" class="text-[10px] font-semibold text-gray-500 hover:text-gray-700">
                                                                Mark read
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-8 text-center text-sm text-gray-500">
                                            No notifications yet
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @unless($hideDashboardButton)
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium">Dashboard</a>
                        @endunless
                        @if($hideDashboardButton)
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button
                                    class="px-4 py-2 text-red-600 hover:text-red-700 transition-colors font-medium">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium">Portal
                            Login</a>
                    @endauth
                </div>

                <!-- Mobile Navigation Toggle -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 transition-colors"
                        aria-label="Toggle mobile menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            </button>
                            </div>
                    </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Home</a>
                <a href="{{ route('about') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">About</a>
                <a href="{{ route('academic-calendar.calendar') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Calendar</a>
                <a href="{{ route('school-news.index') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">School
                    News</a>
                <a href="{{ route('academics') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Academics</a>
                <a href="{{ route('admissions.process') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Admissions</a>
                <a href="{{ route('contact') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Contact Us</a>
                <a href="{{ route('resources.index') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Resources</a>
                <a href="{{ route('gallery.index') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md">Gallery</a>

                @auth
                    @unless($hideDashboardButton ?? false)
                        <a href="{{ route('dashboard') }}"
                            class="block px-3 py-2 bg-blue-600 text-white rounded-md">Dashboard</a>
                    @endunless
                    @if($hideDashboardButton ?? false)
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button class="w-full text-left px-3 py-2 text-red-600 hover:text-red-700">Logout</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 bg-blue-600 text-white rounded-md">Portal
                        Login</a>
                @endauth
            </div>
        </div>
    </header>

    <div class="pt-16">
        @yield('content')
    </div>

    <!-- Footer -->
    <x-footer />

    <!-- Global Toast Container -->
    <div id="toast-container" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[9999] flex flex-col items-center gap-3 w-full max-w-sm pointer-events-none px-4">
        <!-- Toasts will be injected here -->
    </div>

    <!-- Toast Component Template (Hidden) -->
    <template id="toast-template">
        <div class="toast-item pointer-events-auto bg-white border border-gray-100 rounded-2xl shadow-2xl p-4 flex items-center gap-3 w-full animate-toast-in transition-all duration-300 opacity-0 translate-y-[-20px]">
            <div class="toast-icon-container w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="toast-icon fas"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="toast-message text-sm font-bold text-gray-900 leading-tight"></p>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none ml-2">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </template>

    <style>
        @keyframes toast-in {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-toast-in {
            animation: toast-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .toast-item.hide {
            opacity: 0 !important;
            transform: translateY(-20px) scale(0.95) !important;
            transition: all 0.3s ease-in;
        }
    </style>

    <!-- Mobile Menu JavaScript -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        const notificationToggle = document.getElementById('notification-toggle');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationMenu = document.getElementById('notification-menu');

        if (notificationToggle && notificationDropdown && notificationMenu) {
            notificationToggle.addEventListener('click', function (event) {
                event.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!notificationMenu.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        }

        // Global Toast System
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const template = document.getElementById('toast-template');
            const clone = template.content.cloneNode(true);
            const toast = clone.querySelector('.toast-item');
            const iconContainer = clone.querySelector('.toast-icon-container');
            const icon = clone.querySelector('.toast-icon');
            const messageEl = clone.querySelector('.toast-message');
            const closeBtn = clone.querySelector('button');

            messageEl.textContent = message;

            // Set Type Specific Styles
            if (type === 'success') {
                iconContainer.classList.add('bg-green-100');
                icon.classList.add('fa-check-circle', 'text-green-600');
                toast.classList.add('border-l-4', 'border-l-green-500');
            } else if (type === 'error') {
                iconContainer.classList.add('bg-red-100');
                icon.classList.add('fa-exclamation-circle', 'text-red-600');
                toast.classList.add('border-l-4', 'border-l-red-500');
            } else {
                iconContainer.classList.add('bg-blue-100');
                icon.classList.add('fa-info-circle', 'text-blue-600');
                toast.classList.add('border-l-4', 'border-l-blue-500');
            }

            // Close logic
            const removeToast = () => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            };

            closeBtn.onclick = removeToast;
            setTimeout(removeToast, 5000); // Auto-remove after 5s

            container.appendChild(toast);
        };

        // Detect Laravel Session Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            @if(session('status'))
                showToast("{{ session('status') }}", 'info');
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
