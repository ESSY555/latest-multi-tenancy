@extends('layouts.dashboard')

@section('title', 'Weekly Teacher Attendance Summary')

@section('dashboard')
<div class="w-full max-w-full min-w-0 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Weekly Teacher Attendance Summary</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">View attendance records for the selected week (weekdays only)</p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 break-words">Week: {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }} (Monday to Friday)</p>
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
        <form method="GET" action="{{ route('teacher-attendance.weekly-summary') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="week" class="block text-sm font-medium text-gray-700 mb-2">Week</label>
                <input type="week" id="week" name="week" value="{{ $week }}" 
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
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('teacher-attendance.weekly-summary') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Weekly Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 shrink-0">
                    <i class="fas fa-check text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Present</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->where('status', 'present')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 shrink-0">
                    <i class="fas fa-times text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Absent</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->where('status', 'absent')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 shrink-0">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Late</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->where('status', 'late')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">On Leave</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->where('status', 'on_leave')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Summary Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden min-w-0">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-medium text-gray-900">Weekly Attendance Summary</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Detailed breakdown by teacher for the week</p>
        </div>
        
        <div class="overflow-x-auto touch-pan-x">
            <table class="min-w-[48rem] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
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
                    @forelse($summary as $teacherId => $teacherSummary)
                    <tr>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($teacherSummary['teacher']->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $teacherSummary['teacher']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $teacherSummary['teacher']->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $teacherSummary['teacher']->branches->first()->name ?? 'N/A' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $teacherSummary['total_days'] }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $teacherSummary['present'] }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $teacherSummary['absent'] }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $teacherSummary['late'] }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $teacherSummary['on_leave'] }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $teacherSummary['attendance_rate'] }}%"></div>
                                </div>
                                <span class="text-sm font-medium">{{ $teacherSummary['attendance_rate'] }}%</span>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('teacher-attendance.index', ['teacher_id' => $teacherId, 'week' => $week]) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-3 sm:px-6 py-3 sm:py-4 text-center text-sm text-gray-500">
                            No attendance records found for the selected week.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Breakdown -->
    @if(count($summary) > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden min-w-0">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-medium text-gray-900">Daily Breakdown</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Attendance status for each weekday (Monday to Friday)</p>
        </div>
        
        <div class="overflow-x-auto touch-pan-x">
            <table class="min-w-[36rem] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        @foreach($summary[array_key_first($summary)]['week_days'] as $date => $dayInfo)
                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $dayInfo['day_name'] }}<br>
                            <span class="text-xs text-gray-400">{{ $dayInfo['short_date'] }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($summary as $teacherId => $teacherSummary)
                    <tr>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $teacherSummary['teacher']->name }}</div>
                        </td>
                        @foreach($teacherSummary['week_days'] as $date => $dayInfo)
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                            @php
                                $dayAttendance = $attendances->first(function ($attendance) use ($teacherId, $date) {
                                    return (int) $attendance->teacher_id === (int) $teacherId
                                        && optional($attendance->date)->format('Y-m-d') === $date;
                                });
                                $status = $dayAttendance ? $dayAttendance->status : null;
                            @endphp
                            
                            @if($status === 'present')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Present
                                </span>
                            @elseif($status === 'absent')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Absent
                                </span>
                            @elseif($status === 'late')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Late
                                </span>
                            @elseif($status === 'on_leave')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-calendar-alt mr-1"></i>Leave
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <i class="fas fa-minus mr-1"></i>No Record
                                </span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

