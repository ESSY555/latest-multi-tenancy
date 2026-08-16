@extends('layouts.dashboard')

@section('title', 'Teacher Attendance')

@section('dashboard')
<div class="w-full max-w-full min-w-0 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Teacher Attendance</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Manage and track teacher attendance records</p>
            <!-- Debug Info -->
            <p class="text-xs sm:text-sm text-gray-500 mt-1 break-words">Current Role: <span class="font-medium">{{ $currentRole }}</span></p>
            @if(in_array($currentRole, ['admin', 'super_admin']))
                <p class="text-xs sm:text-sm text-green-600 mt-1">✓ You have edit permissions</p>
            @else
                <p class="text-xs sm:text-sm text-orange-600 mt-1">⚠ You can only view records</p>
            @endif
        </div>
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2 shrink-0 w-full lg:w-auto">
            <a href="{{ route('teacher-attendance.weekly-summary') }}" 
               class="inline-flex justify-center items-center px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-calendar-week mr-2"></i>Weekly Summary
            </a>
            <a href="{{ route('teacher-attendance.monthly-summary') }}" 
               class="inline-flex justify-center items-center px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-chart-bar mr-2"></i>Monthly Summary
            </a>
            <a href="{{ route('teacher-attendance.create') }}" 
               class="inline-flex justify-center items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Record Attendance
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 shrink-0">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-sm font-medium text-gray-600">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['present'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['absent'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['late'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['on_leave'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 min-w-0">
        <form method="GET" action="{{ route('teacher-attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
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
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="late" {{ $status === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="on_leave" {{ $status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex flex-col sm:flex-row sm:items-end gap-2 sm:gap-3">
                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('teacher-attendance.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden min-w-0">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-medium text-gray-900">Attendance Records</h3>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto touch-pan-x">
                <table class="min-w-[56rem] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            @if($currentRole === 'super_admin')
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            @endif
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 align-top">
                                    <div class="flex items-start gap-3 min-w-0 max-w-[14rem] sm:max-w-none">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ substr($attendance->teacher->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900 break-words">{{ $attendance->teacher->name }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500 break-all">{{ $attendance->teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                @if($currentRole === 'super_admin')
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 align-top">
                                    <span class="break-words">{{ $attendance->branch->name }}</span>
                                </td>
                                @endif
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 align-top">
                                    {{ $attendance->formatted_date }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap align-top">
                                    {!! $attendance->status_badge !!}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 align-top">
                                    {{ $attendance->formatted_time_in }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 align-top">
                                    {{ $attendance->formatted_time_out }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 align-top max-w-[10rem] sm:max-w-none">
                                    <span class="break-words">{{ $attendance->markedBy->name ?? 'System' }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 align-top max-w-[12rem] sm:max-w-xs">
                                    <span class="break-words">{{ $attendance->reason ?? '-' }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm font-medium align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Button - All users can see -->
                                        <a href="{{ route('teacher-attendance.show', $attendance) }}" 
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2 py-1 rounded text-xs sm:text-sm whitespace-nowrap" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <!-- Edit Button - Only Admin and Super Admin -->
                                        @if(in_array($currentRole, ['admin', 'super_admin']))
                                        <a href="{{ route('teacher-attendance.edit', $attendance) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded text-xs sm:text-sm whitespace-nowrap" 
                                           title="Edit Record">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @endif
                                        
                                        <!-- Delete Button - Only Admin and Super Admin -->
                                        @if(in_array($currentRole, ['admin', 'super_admin']))
                                        <form action="{{ route('teacher-attendance.destroy', $attendance) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded text-xs sm:text-sm whitespace-nowrap" title="Delete Record">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 overflow-x-auto">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No attendance records found</h3>
                <p class="text-gray-500 mb-6">No attendance records match your current filters.</p>
                <a href="{{ route('teacher-attendance.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Record First Attendance
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

