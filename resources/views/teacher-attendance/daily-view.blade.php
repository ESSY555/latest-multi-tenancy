@extends('layouts.dashboard')

@section('title', 'Daily Teacher Attendance')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Daily Teacher Attendance</h1>
            <p class="text-gray-600">View today's attendance for all teachers in {{ $branch->name }}</p>
        </div>
        <div class="text-sm text-gray-500">
            <i class="fas fa-calendar mr-1"></i>{{ $today }}
        </div>
    </div>

    <!-- My Status Today -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">My Attendance Status Today</h3>
        
        @if($todayAttendance)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-check text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-green-900">Attendance Marked</h4>
                            <p class="text-green-700">Your attendance has been recorded for today</p>
                        </div>
                    </div>
                    <div class="text-right">
                        {!! $todayAttendance->status_badge !!}
                        @if($todayAttendance->time_in)
                            <p class="text-sm text-green-600 mt-1">Time In: {{ $todayAttendance->formatted_time_in }}</p>
                        @endif
                        @if($todayAttendance->markedBy)
                            <p class="text-sm text-green-600 mt-1">Marked by: {{ $todayAttendance->markedBy->name }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-yellow-900">Not Marked Yet</h4>
                        <p class="text-yellow-700">Your attendance has not been marked for today. Please contact your administrator.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Daily Attendance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalTeachers = $dailyAttendance->count();
            $presentCount = $dailyAttendance->where('status', 'present')->count();
            $absentCount = $dailyAttendance->where('status', 'absent')->count();
            $lateCount = $dailyAttendance->where('status', 'late')->count();
            $onLeaveCount = $dailyAttendance->where('status', 'on_leave')->count();
            $notMarkedCount = $totalTeachers - $totalTeachers; // This will be calculated differently
        @endphp

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Teachers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTeachers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Present</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $presentCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Late</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $lateCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Absent</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $absentCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Today's Teacher Attendance - {{ $branch->name }}</h3>
        </div>

        @if($dailyAttendance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dailyAttendance as $attendance)
                            <tr class="hover:bg-gray-50 {{ $attendance->teacher_id === auth()->id() ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ substr($attendance->teacher->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $attendance->teacher->name }}
                                                @if($attendance->teacher_id === auth()->id())
                                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        You
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $attendance->teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $attendance->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_time_in }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_time_out }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->markedBy->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $attendance->reason }}">
                                        {{ $attendance->reason ? Str::limit($attendance->reason, 50) : '-' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No attendance records found</h3>
                <p class="text-gray-500">No teacher attendance records found for today.</p>
            </div>
        @endif
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>Important Information
        </h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• This view shows all teachers' attendance for today in your branch</li>
            <li>• Your record is highlighted in blue with a "You" badge</li>
            <li>• Contact your administrator if you notice any discrepancies</li>
            <li>• Only administrators can mark or modify attendance records</li>
        </ul>
    </div>
</div>
@endsection

