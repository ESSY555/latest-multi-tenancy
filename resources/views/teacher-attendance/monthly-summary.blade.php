@extends('layouts.dashboard')

@section('title', 'Teacher Attendance Monthly Summary')

@section('dashboard')
<div class="w-full max-w-full min-w-0 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Monthly Summary</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Teacher attendance summary and statistics</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 shrink-0 w-full lg:w-auto">
            <a href="{{ route('teacher-attendance.index') }}" 
               class="inline-flex justify-center items-center px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            <a href="{{ route('teacher-attendance.create') }}" 
               class="inline-flex justify-center items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Record Attendance
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
        <form method="GET" action="{{ route('teacher-attendance.monthly-summary') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <input type="month" id="month" name="month" value="{{ $month }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher</label>
                <select id="teacher_id" name="teacher_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($currentRole === 'super_admin')
            <div>
                <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                <select id="branch_id" name="branch_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchFilter == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="sm:col-span-2 lg:col-span-4 flex flex-col sm:flex-row sm:items-end gap-2 sm:gap-3">
                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Generate Report
                </button>
                <a href="{{ route('teacher-attendance.monthly-summary') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    @if(count($summary) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @php
            $totalDays = collect($summary)->sum('total_days');
            $totalPresent = collect($summary)->sum('present');
            $totalAbsent = collect($summary)->sum('absent');
            $totalLate = collect($summary)->sum('late');
            $totalOnLeave = collect($summary)->sum('on_leave');
            $overallRate = $totalDays > 0 ? round(($totalPresent + $totalLate) / $totalDays * 100, 2) : 0;
        @endphp

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full shrink-0 bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Days</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDays }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full shrink-0 bg-green-100 text-green-600">
                    <i class="fas fa-check text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Present Days</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPresent }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full shrink-0 bg-red-100 text-red-600">
                    <i class="fas fa-times text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Absent Days</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAbsent }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full shrink-0 bg-purple-100 text-purple-600">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overallRate }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Summary Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden min-w-0">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-medium text-gray-900 break-words">Teacher Summary for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>
        </div>

        <div class="overflow-x-auto touch-pan-x">
            <table class="min-w-[48rem] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        @if($currentRole === 'super_admin')
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        @endif
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Leave</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($summary as $teacherId => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($data['teacher']->name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3 sm:ml-4 min-w-0">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['teacher']->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $data['teacher']->email }}</div>
                                    </div>
                                </div>
                            </td>
                            @if($currentRole === 'super_admin')
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['teacher']->branches->first()->name ?? 'N/A' }}
                            </td>
                            @endif
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['total_days'] }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $data['present'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $data['absent'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $data['late'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $data['on_leave'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $data['attendance_rate'] }}%</span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['attendance_rate'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('teacher-attendance.index', ['teacher_id' => $teacherId, 'month' => $month]) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden min-w-0">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-medium text-gray-900">Detailed Records</h3>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto touch-pan-x">
                <table class="min-w-[44rem] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            @if($currentRole === 'super_admin')
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            @endif
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_date }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->teacher->name }}
                                </td>
                                @if($currentRole === 'super_admin')
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->branch->name }}
                                </td>
                                @endif
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    {!! $attendance->status_badge !!}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_time_in }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->formatted_time_out }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900">
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
                <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No records found</h3>
                <p class="text-gray-500">No attendance records match your current filters.</p>
            </div>
        @endif
    </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No summary data available</h3>
            <p class="text-gray-500">No attendance records found for the selected criteria.</p>
        </div>
    @endif
</div>
@endsection

