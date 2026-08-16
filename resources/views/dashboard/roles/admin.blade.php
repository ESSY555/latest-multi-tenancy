@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('dashboard')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="mt-2 text-gray-600">Manage academics and users for {{ $branch->name }}</p>
            </div>

            <!-- Admin Info Banner -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg shadow-lg p-6 mb-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-purple-100">Administrator</p>
                        <p class="text-purple-100">{{ $branch->name }}</p>
                    </div>
                    <div class="text-left sm:text-right text-white">
                        <div class="text-4xl font-bold">{{ $stats['students'] }}</div>
                        <div class="text-purple-100">Total Students</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Classes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['classes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Subjects</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['subjects'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Teachers</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['teachers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Students</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['students'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-pink-100 rounded-lg">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Gallery Items</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['gallery_items'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('academic-calendar.index') }}" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Manage Calendar
                    </a>
                    <a href="{{ route('dashboard.calendar') }}" class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Calendar
                    </a>
                    <a href="{{ route('gallery.admin') }}" class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Manage Gallery
                    </a>
                    <a href="{{ route('admin.form-teacher-assignments.index') }}" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Manage Form Teachers
                    </a>
                </div>
            </div>

            <!-- Pending Lesson Plan Reviews -->
            @if(isset($stats['pending_lesson_plans']) && $stats['pending_lesson_plans'] > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-yellow-800">Lesson Plans Pending Review</h3>
                            <p class="text-yellow-700">You have <strong>{{ $stats['pending_lesson_plans'] }}</strong> lesson plan(s) waiting for your approval or rejection.</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('lesson-plans.index', ['status' => 'submitted']) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Review Now
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Charts Section -->
            @if(isset($charts) && count($charts) > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Analytics & Insights</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($charts as $chart)
                        <x-dashboard-chart :chart="$chart" />
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Main Content -->
            {{-- <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Recent Assignments -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Assignments</h3>
                            <div class="space-y-3">
                                @forelse($recentAssignments as $a)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $a->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $a->schoolClass->name }}</p>
                                        <p class="text-xs text-gray-400">Due: {{ $a->due_date ? $a->due_date->format('M d, Y') : 'No
                                            due date' }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($a->due_date && $a->due_date->isPast())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Overdue
                                        </span>
                                        @elseif($a->due_date && $a->due_date->diffInDays(now()) <= 3) <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Due Soon
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                            @endif
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-500 text-center py-4">No assignments available.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Recent Results -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Results</h3>
                            <div class="space-y-3">
                                @forelse($recentResults as $r)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $r->student->name }} - {{ $r->subject }}</p>
                                        <p class="text-sm text-gray-500">{{ $r->schoolClass->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $r->term }} • {{ $r->academic_year }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $r->score }}%
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-500 text-center py-4">No results available yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection



