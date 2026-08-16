@extends('layouts.dashboard')

@section('title', 'My Attendance')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">My Attendance</h1>
            <p class="text-gray-600">View your attendance records and status</p>
        </div>
        <div class="text-sm text-gray-500">
            <i class="fas fa-calendar mr-1"></i>{{ $today }}
        </div>
    </div>

    <!-- Today's Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Attendance Status</h3>
        
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

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Days</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['total_days'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['present'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['late'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyStats['attendance_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Attendance Records (Last 30 Days)</h3>
        </div>

        @if($recentAttendance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAttendance as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_date }}
                                    @if($attendance->date->format('Y-m-d') === $today)
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Today
                                        </span>
                                    @endif
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
                <p class="text-gray-500">No attendance records found for the last 30 days.</p>
            </div>
        @endif
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>Important Information
        </h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Your attendance is marked by administrators</li>
            <li>• You can view your attendance history here</li>
            <li>• Contact your administrator if you notice any discrepancies</li>
            <li>• Monthly statistics are calculated automatically</li>
        </ul>
    </div>
</div>
@endsection

