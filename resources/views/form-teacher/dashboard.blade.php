@extends('layouts.dashboard')

@section('title', 'Form Teacher Dashboard')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Form Teacher Dashboard</h1>
            <p class="mt-2 text-gray-600">Managing {{ $class->name }} at {{ $branch->name }}</p>
        </div>

        <!-- Form Teacher Info Banner -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-purple-100">Form Teacher</p>
                    <p class="text-purple-100">{{ $class->name }} • {{ $branch->name }}</p>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold">{{ $students->count() }}</div>
                    <div class="text-purple-100">Students</div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Present Today</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayAttendance->where('status', 'present')->count() }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Active Assignments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $recentAssignments->where('is_published', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Recent Remarks</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $recentRemarks->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('form-teacher.attendance') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Take Attendance</p>
                        <p class="text-sm text-gray-500">Record daily attendance</p>
                    </div>
                </a>

                <a href="{{ route('form-teacher.students') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">View Students</p>
                        <p class="text-sm text-gray-500">Student profiles & records</p>
                    </div>
                </a>

                <a href="{{ route('form-teacher.assignments') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Monitor Assignments</p>
                        <p class="text-sm text-gray-500">Track submissions & grades</p>
                    </div>
                </a>

                <a href="{{ route('form-teacher.announcements') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.881A10 10 0 008.5 5.5A10 10 0 004.5 5.5A10 10 0 001 5.881A10 10 0 000 7.5A10 10 0 001 9.119A10 10 0 004.5 9.5A10 10 0 008.5 9.5A10 10 0 0011 9.119A10 10 0 0012 7.5A10 10 0 0011 5.881z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Send Announcements</p>
                        <p class="text-sm text-gray-500">Communicate with parents</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Today's Attendance Summary -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Today's Attendance</h3>
                        <a href="{{ route('form-teacher.attendance') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    @if($todayAttendance->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayAttendance->take(5) as $attendance)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-gray-600">{{ substr($attendance->student->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $attendance->student->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $attendance->student->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                       ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' : 
                                       ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No attendance recorded for today.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Assignments -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Assignments</h3>
                        <a href="{{ route('form-teacher.assignments') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    @if($recentAssignments->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentAssignments as $assignment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $assignment->title }}</p>
                                    <p class="text-sm text-gray-500">By {{ $assignment->teacher_name ?? $assignment->teacher->name }}</p>
                                    <p class="text-xs text-gray-400">Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'No due date' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $assignment->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $assignment->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $assignment->submissions->count() }} submissions</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No assignments found.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Student Remarks -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Remarks</h3>
                        <a href="{{ route('form-teacher.remarks') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    @if($recentRemarks->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentRemarks as $remark)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-medium text-gray-900">{{ $remark->student->name }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $remark->severity === 'positive' ? 'bg-green-100 text-green-800' : 
                                           ($remark->severity === 'concern' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($remark->severity === 'serious' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($remark->severity) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $remark->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $remark->date->format('M d, Y') }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No remarks recorded.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Reports -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Quick Reports</h3>
                        <a href="{{ route('form-teacher.reports') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    <div class="space-y-4">
                        <a href="{{ route('form-teacher.reports') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Class Performance Report</p>
                                <p class="text-sm text-gray-500">Attendance, grades & analytics</p>
                            </div>
                        </a>

                        <a href="{{ route('form-teacher.students') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Student Records</p>
                                <p class="text-sm text-gray-500">Individual student profiles</p>
                            </div>
                        </a>

                        <a href="{{ route('form-teacher.announcements') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.881A10 10 0 008.5 5.5A10 10 0 004.5 5.5A10 10 0 001 5.881A10 10 0 000 7.5A10 10 0 001 9.119A10 10 0 004.5 9.5A10 10 0 008.5 9.5A10 10 0 0011 9.119A10 10 0 0012 7.5A10 10 0 0011 5.881z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Communication Center</p>
                                <p class="text-sm text-gray-500">Send announcements to parents</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

