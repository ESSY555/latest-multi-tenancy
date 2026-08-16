@extends('layouts.dashboard')

@section('title', 'Edit Teacher Attendance')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Teacher Attendance</h1>
            <p class="text-gray-600">Update attendance record for {{ $teacherAttendance->teacher->name }}</p>
        </div>
        <a href="{{ route('teacher-attendance.index') }}" 
           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('teacher-attendance.update', $teacherAttendance) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teacher Selection -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Teacher <span class="text-red-500">*</span>
                    </label>
                    <select id="teacher_id" name="teacher_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('teacher_id') border-red-500 @enderror">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $teacherAttendance->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Selection (Super Admin Only) -->
                @if($currentRole === 'super_admin')
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('branch_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $teacherAttendance->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                        <span class="text-sm text-gray-500 font-normal">(Read-only)</span>
                    </label>
                    <input type="date" id="date" name="date" value="{{ old('date', $teacherAttendance->date->format('Y-m-d')) }}" required readonly
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 cursor-not-allowed @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Attendance Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        <option value="present" {{ old('status', $teacherAttendance->status) === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ old('status', $teacherAttendance->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ old('status', $teacherAttendance->status) === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="on_leave" {{ old('status', $teacherAttendance->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time In -->
                <div>
                    <label for="time_in" class="block text-sm font-medium text-gray-700 mb-2">
                        Time In
                        <span class="text-sm text-gray-500 font-normal">(Read-only)</span>
                    </label>
                    <input type="time" id="time_in" name="time_in" value="{{ old('time_in', $teacherAttendance->time_in ? $teacherAttendance->time_in->format('H:i') : '') }}" readonly
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 cursor-not-allowed @error('time_in') border-red-500 @enderror">
                    @error('time_in')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Out -->
                <div>
                    <label for="time_out" class="block text-sm font-medium text-gray-700 mb-2">
                        Time Out
                        <span class="text-sm text-gray-500 font-normal">(Optional - Set when teacher checks out)</span>
                    </label>
                    <input type="time" id="time_out" name="time_out" value="{{ old('time_out', $teacherAttendance->time_out ? $teacherAttendance->time_out->format('H:i') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('time_out') border-red-500 @enderror">
                    @error('time_out')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason (Optional)
                </label>
                <textarea id="reason" name="reason" rows="3" 
                          placeholder="Enter reason for absence, lateness, or leave..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror">{{ old('reason', $teacherAttendance->reason) }}</textarea>
                @error('reason')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('teacher-attendance.show', $teacherAttendance) }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Attendance
                </button>
            </div>
        </form>
    </div>

    <!-- Current Record Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>Current Record Information
        </h3>
        <div class="text-sm text-blue-800 space-y-1">
            <p>• Last updated: {{ $teacherAttendance->updated_at->format('M d, Y g:i A') }}</p>
            <p>• Marked by: {{ $teacherAttendance->markedBy->name ?? 'System' }}</p>
            <p>• Record ID: {{ $teacherAttendance->id }}</p>
            <p>• Time In is read-only to preserve check-in time. Time Out can be edited.</p>
        </div>
    </div>
</div>

<script>
    // Clear reason field when status changes to present
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const reasonField = document.getElementById('reason');
        
        if (status === 'present') {
            reasonField.value = '';
        }
    });
</script>
@endsection

