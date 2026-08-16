@extends('layouts.dashboard')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Student Dashboard</h1>
            <p class="mt-2 text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>

        @if(!$currentEnrollment)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>No Enrollment Found:</strong> You are not currently enrolled in any classes in this branch. Please contact your administrator to get enrolled.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Student Info Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    @if($currentEnrollment)
                        <p class="text-blue-100">{{ $currentEnrollment->schoolClass->name ?? 'Not Enrolled' }}</p>
                        <p class="text-blue-100">{{ $currentEnrollment->schoolClass->branch->name ?? '' }}</p>
                    @else
                        <p class="text-blue-100">Not Enrolled in Any Class</p>
                        <p class="text-blue-100">Please contact your administrator</p>
                    @endif
                </div>
                <div class="text-left sm:text-right text-white">
                    <div class="text-4xl font-bold">{{ $gpa }}</div>
                    <div class="text-blue-100">GPA</div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Attendance</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $attendanceSummary['percentage'] }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Subjects</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $recentResults->unique('subject')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Assignments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $recentAssignments ? $recentAssignments->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Results</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $recentResults ? $recentResults->count() : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        @if(isset($charts) && count($charts) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Analytics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($charts as $chart)
                    <x-dashboard-chart :chart="$chart" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Announcements -->
        @if($recentAnnouncements && $recentAnnouncements->count() > 0)
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Announcements</h3>
                    <a href="{{ route('student.announcements') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($recentAnnouncements as $announcement)
                    <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $announcement->title }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $announcement->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                           ($announcement->priority === 'high' ? 'bg-yellow-100 text-yellow-800' :
                                           ($announcement->priority === 'medium' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex items-center text-xs text-gray-500 space-x-3">
                                    <span><i class="fas fa-user mr-1"></i>{{ $announcement->formTeacher->name }}</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $announcement->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Results -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Results</h3>
                        <div class="space-y-3">
                            @forelse($recentResults as $result)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $result->subject->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $result->schoolClass->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $result->grade ?? 'N/A' }}
                                    </span>
                                    <p class="text-sm text-gray-500">{{ $result->total ?? 'N/A' }}%</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No results available yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Assignments -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Assignments</h3>
                        <div class="space-y-3">
                            @forelse($recentAssignments as $assignment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $assignment->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $assignment->schoolClass->name }}</p>
                                    <p class="text-xs text-gray-400">Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'No due date' }}</p>
                                </div>
                                <div class="text-right">
                                    @if($assignment->due_date && $assignment->due_date->isPast())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Overdue
                                        </span>
                                    @elseif($assignment->due_date && $assignment->due_date->diffInDays(now()) <= 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Due Soon
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



