@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-row flex-nowrap items-stretch">
        @auth
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-black/40 hidden md:hidden"></div>
        <aside id="sidebar" class="hidden md:block shrink-0 bg-white border-r shadow-lg overflow-y-auto md:w-64 md:max-w-none md:h-auto">
            <div class="p-4 text-lg font-semibold"><a href="{{ url('/') }}" class="hover:underline">Bezaleel</a></div>
            <div class="px-4 pb-2 md:hidden">
                <button id="sidebar-close-btn" type="button"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm bg-white hover:bg-gray-50">
                    Close
                </button>
            </div>
            <nav class="px-2 text-sm space-y-1">
                @if(app()->bound('currentBranch'))
                    <div class="mb-4 px-3 py-3 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <p class="text-[10px] uppercase tracking-wider text-indigo-500 font-bold mb-1">Current Context</p>
                        <div class="flex items-center text-indigo-900 min-w-0">
                            <i class="fas fa-university mr-2 text-indigo-400 flex-shrink-0"></i>
                            <span class="font-semibold truncate block">{{ app('currentBranch')->name }}</span>
                        </div>
                        @if($currentRole === 'super_admin')
                            <a href="{{ route('dashboard.select-branch') }}" class="mt-2 block text-[10px] text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center">
                                <i class="fas fa-exchange-alt mr-1"></i>Switch Branch
                            </a>
                        @endif
                    </div>
                @endif

                <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
                    style="background-color: rgb(37,99,235); color: white;"
                    onmouseover="this.style.backgroundColor='rgb(29,78,216)'"
                    onmouseout="this.style.backgroundColor='rgb(37,99,235)'" href="{{ route('dashboard') }}">Dashboard</a>

                {{-- Role-specific sidebars --}}
                @if($currentRole === 'super_admin')
                    <x-admin.super-admin-sidebar />
                @elseif($currentRole === 'admin')
                    <x-admin.branch-admin-sidebar />
                @elseif($currentRole === 'teacher')
                    <x-teacher-sidebar />
                @elseif($currentRole === 'form_teacher')
                    {{-- Form teachers get both teacher features AND form teacher features --}}
                    <x-teacher-sidebar />
                    <x-form-teacher-sidebar />
                @elseif($currentRole === 'student' || request()->routeIs('student.*'))
                    <x-student-sidebar />
                @elseif($currentRole === 'parent')
                    <div class="mt-4 text-xs uppercase text-gray-500 px-3">Parent</div>
                    <div class="px-3 pt-2 space-y-2">
                        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
                            style="background-color: rgb(236,81,34); color: white;"
                            onmouseover="this.style.backgroundColor='rgb(220,76,24)'"
                            onmouseout="this.style.backgroundColor='rgb(236,81,34)'" href="{{ route('result.index') }}">Children
                            Results</a>
                    </div>

                    <div class="mt-4 text-xs uppercase text-gray-500 px-3">Resources & Export</div>
                    <div class="px-3 pt-2 space-y-2">
                        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
                            style="background-color: rgb(236,81,34); color: white;"
                            onmouseover="this.style.backgroundColor='rgb(220,76,24)'"
                            onmouseout="this.style.backgroundColor='rgb(236,81,34)'" href="{{ route('resources.index') }}">View
                            Resources</a>
                        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
                            style="background-color: rgb(37,99,235); color: white;"
                            onmouseover="this.style.backgroundColor='rgb(29,78,216)'"
                            onmouseout="this.style.backgroundColor='rgb(37,99,235)'"
                            href="{{ route('syllabus.export.pdf') }}">Export Syllabus PDF</a>
                        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
                            style="background-color: rgb(236,81,34); color: white;"
                            onmouseover="this.style.backgroundColor='rgb(220,76,24)'"
                            onmouseout="this.style.backgroundColor='rgb(236,81,34)'"
                            href="{{ route('syllabus.export.excel') }}">Export Syllabus Excel</a>
                    </div>
                @endif
            </nav>
        </aside>
        @endauth
        <main
            class="flex-1 min-w-0 p-4 sm:p-6 space-y-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
            @auth
                <div class="md:hidden mb-4">
                    <button id="sidebar-open-btn" type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium bg-white hover:bg-gray-50">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Menu
                    </button>
                </div>
            @endauth
            <x-flash-alerts />
            @yield('dashboard')
        </main>
    </div>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-40', 'w-72', 'max-w-[85vw]');
            }
            if (backdrop) backdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && window.innerWidth < 768) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-40', 'w-72', 'max-w-[85vw]');
            }
            if (backdrop) backdrop.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('sidebar-open-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar) return;

            if (openBtn) {
                openBtn.addEventListener('click', openSidebar);
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
@endsection
