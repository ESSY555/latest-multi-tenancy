@extends('layouts.dashboard')

@section('title', 'Teacher Attendance Details')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Teacher Attendance Details</h1>
            <p class="text-gray-600">View detailed attendance information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('teacher-attendance.index') }}" 
               class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            <a href="{{ route('teacher-attendance.edit', $teacherAttendance) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <!-- Attendance Details -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Teacher Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-900 mb-4">Teacher Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-blue-800">Name:</span>
                        <span class="text-blue-700">{{ $teacherAttendance->teacher->name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Email:</span>
                        <span class="text-blue-700">{{ $teacherAttendance->teacher->email }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Branch:</span>
                        <span class="text-blue-700">{{ $teacherAttendance->branch->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Attendance Information -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-green-900 mb-4">Attendance Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-green-800">Date:</span>
                        <span class="text-green-700">{{ $teacherAttendance->formatted_date }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-green-800">Status:</span>
                        <span class="text-green-700">{!! $teacherAttendance->status_badge !!}</span>
                    </div>
                    <div>
                        <span class="font-medium text-green-800">Marked By:</span>
                        <span class="text-green-700">{{ $teacherAttendance->markedBy->name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Information -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Time Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="font-medium text-gray-800">Time In:</span>
                    <span class="text-gray-700 ml-2">{{ $teacherAttendance->formatted_time_in }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-800">Time Out:</span>
                    <span class="text-gray-700 ml-2">{{ $teacherAttendance->formatted_time_out }}</span>
                </div>
            </div>
        </div>

        <!-- Reason (if any) -->
        @if($teacherAttendance->reason)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-yellow-900 mb-2">Reason</h3>
            <p class="text-yellow-800">{{ $teacherAttendance->reason }}</p>
        </div>
        @endif

        <!-- Record Information -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Record Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="font-medium text-gray-800">Created:</span>
                    <span class="text-gray-700 ml-2">{{ $teacherAttendance->created_at->format('M d, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-800">Last Updated:</span>
                    <span class="text-gray-700 ml-2">{{ $teacherAttendance->updated_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-6 border-t">
        <div class="flex items-center space-x-3">
            <a href="{{ route('teacher-attendance.index') }}" 
               class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-list mr-2"></i>Back to List
            </a>
            <a href="{{ route('teacher-attendance.edit', $teacherAttendance) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Record
            </a>
        </div>
        <form action="{{ route('teacher-attendance.destroy', $teacherAttendance) }}" 
              method="POST" class="inline" 
              onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete Record
            </button>
        </form>
    </div>
</div>
@endsection

