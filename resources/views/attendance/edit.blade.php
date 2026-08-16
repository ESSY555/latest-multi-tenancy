@extends('layouts.dashboard')

@section('title', 'Edit Attendance')

@section('dashboard')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Attendance</h1>
        <a href="{{ route('attendance.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Attendance
        </a>
    </div>
    
    <form method="POST" action="{{ route('attendance.update', $attendance) }}" class="space-y-6 bg-white p-6 rounded-xl shadow">
        @csrf
        @method('PUT')
        
        <!-- Student Info Display -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Student Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-blue-800">Name:</span>
                    <span class="text-blue-700">{{ $attendance->student->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-800">Email:</span>
                    <span class="text-blue-700">{{ $attendance->student->email }}</span>
                </div>
            </div>
        </div>
        
        <!-- Class and Date Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                <select name="school_class_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" {{ $attendance->school_class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_class_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" value="{{ old('date', $attendance->date ? $attendance->date->format('Y-m-d') : '') }}" 
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Attendance Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Attendance Status</label>
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="radio" name="status" value="present" 
                           {{ $attendance->status === 'present' ? 'checked' : '' }}
                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-900">Present</span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Student was present
                    </span>
                </label>
                
                <label class="flex items-center">
                    <input type="radio" name="status" value="absent" 
                           {{ $attendance->status === 'absent' ? 'checked' : '' }}
                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-900">Absent</span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Student was absent
                    </span>
                </label>
                
                <label class="flex items-center">
                    <input type="radio" name="status" value="late" 
                           {{ $attendance->status === 'late' ? 'checked' : '' }}
                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-900">Late</span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Student arrived late
                    </span>
                </label>
            </div>
            @error('status')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t">
            <div class="text-sm text-gray-500">
                <span>Last updated: {{ $attendance->updated_at->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Attendance
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

