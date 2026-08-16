@extends('layouts.dashboard')

@section('title', 'Class Reports')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Class Reports</h1>
            <p class="mt-2 text-gray-600">Performance analytics for {{ $class->name }}</p>
        </div>

        <!-- Stats -->
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
                        <p class="text-sm font-medium text-gray-500">Avg Attendance</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $students->count() > 0 ? round($students->sum(function($enrollment) use ($attendanceStats) { return $attendanceStats[$enrollment->student->id]['percentage'] ?? 0; }) / $students->count(), 1) : 0 }}%
                        </p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Assignment::where('school_class_id', $class->id)->where('is_published', true)->count() }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Total Results</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Result::where('school_class_id', $class->id)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Student Performance Overview</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Attendance and performance metrics for each student</p>
            </div>
            
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $enrollment)
                        @php
                            $stats = $attendanceStats[$enrollment->student->id] ?? ['total' => 0, 'present' => 0, 'percentage' => 0];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($enrollment->student->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $enrollment->student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $stats['percentage'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $stats['present'] }} / {{ $stats['total'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $results = \App\Models\Result::where('student_id', $enrollment->student->id)
                                        ->where('school_class_id', $class->id)
                                        ->get();
                                    $avgScore = $results->count() > 0 ? $results->avg('score') : 0;
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $avgScore >= 80 ? 'bg-green-100 text-green-800' : 
                                       ($avgScore >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ round($avgScore, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('form-teacher.students.show', $enrollment->student->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">View Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                <p class="mt-1 text-sm text-gray-500">No students are currently enrolled in this class.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('form-teacher.attendance') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Take Attendance</p>
                            <p class="text-sm text-gray-500">Record daily attendance</p>
                        </div>
                    </a>
                    <a href="{{ route('form-teacher.remarks.create') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Add Remark</p>
                            <p class="text-sm text-gray-500">Add student remark</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Communication</h3>
                <div class="space-y-3">
                    <a href="{{ route('form-teacher.announcements.create') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.881A10 10 0 008.5 5.5A10 10 0 004.5 5.5A10 10 0 001 5.881A10 10 0 000 7.5A10 10 0 001 9.119A10 10 0 004.5 9.5A10 10 0 008.5 9.5A10 10 0 0011 9.119A10 10 0 0012 7.5A10 10 0 0011 5.881z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">Send Announcement</p>
                            <p class="text-sm text-gray-500">Communicate with parents</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Analytics</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">Class Average</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $students->count() > 0 ? round($students->sum(function($enrollment) use ($attendanceStats) { return $attendanceStats[$enrollment->student->id]['percentage'] ?? 0; }) / $students->count(), 1) : 0 }}%
                        </p>
                        <p class="text-xs text-gray-500">Attendance Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

