@extends('layouts.dashboard')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Parent Dashboard</h1>
            <p class="mt-2 text-gray-600">Monitor your children's academic progress</p>
        </div>

        <!-- Parent Info Banner -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-indigo-100">Parent</p>
                    <p class="text-indigo-100">{{ $children->count() }} children enrolled</p>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold">{{ round($children->avg(function($child) use ($overviewData) { return $overviewData[$child->id]['gpa']; }), 2) }}</div>
                    <div class="text-indigo-100">Average GPA</div>
                </div>
            </div>
        </div>

        <!-- Children Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @foreach($children as $child)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">{{ $child->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                            {{ $overviewData[$child->id]['class'] }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-indigo-600">{{ $overviewData[$child->id]['gpa'] }}</p>
                            <p class="text-xs text-gray-500">GPA</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $overviewData[$child->id]['attendance']['percentage'] }}%</p>
                            <p class="text-xs text-gray-500">Attendance</p>
                        </div>
                    </div>

                    <!-- Recent Results -->
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Recent Results</h4>
                        <div class="space-y-2">
                            @forelse($overviewData[$child->id]['recent_results'] as $result)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">{{ $result->subject->name }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    @if($result->grade == 'A') bg-green-100 text-green-800
                                    @elseif($result->grade == 'B') bg-blue-100 text-blue-800
                                    @elseif($result->grade == 'C') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $result->grade ?? 'N/A' }}
                                </span>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 text-center">No results yet</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pending Assignments -->
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Pending Assignments</h4>
                        <div class="space-y-2">
                            @forelse($overviewData[$child->id]['pending_assignments'] as $assignment)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600 truncate">{{ $assignment->title }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    @if($assignment->due_date && $assignment->due_date->diffInDays(now()) <= 1) bg-red-100 text-red-800
                                    @elseif($assignment->due_date && $assignment->due_date->diffInDays(now()) <= 3) bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    @if($assignment->due_date)
                                        {{ $assignment->due_date->diffForHumans() }}
                                    @else
                                        No due date
                                    @endif
                                </span>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 text-center">No pending assignments</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('parent.child.details', $child->id) }}" 
                           class="flex-1 bg-indigo-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors cursor-pointer">
                            View Details
                        </a>
                        <a href="{{ route('parent.child.grades', $child->id) }}" 
                           class="flex-1 bg-green-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors cursor-pointer">
                            Grades
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary Statistics -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Family Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $children->count() }}</div>
                    <div class="text-sm text-gray-500">Children</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">
                        {{ round($children->avg(function($child) use ($overviewData) { return $overviewData[$child->id]['gpa']; }), 2) }}
                    </div>
                    <div class="text-sm text-gray-500">Average GPA</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        {{ round($children->avg(function($child) use ($overviewData) { return $overviewData[$child->id]['attendance']['percentage']; }), 1) }}%
                    </div>
                    <div class="text-sm text-gray-500">Avg Attendance</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">
                        {{ $children->sum(function($child) use ($overviewData) { return count($overviewData[$child->id]['pending_assignments']); }) }}
                    </div>
                    <div class="text-sm text-gray-500">Total Pending</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Teachers
                </button>
                <button class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 0a4 4 0 00-4 4v6m8-6a4 4 0 014 4v6m-8-6a4 4 0 00-4 4v6"></path>
                    </svg>
                    View Calendar
                </button>
                <button class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Reports
                </button>
            </div>
        </div>
    </div>
</div>
@endsection



