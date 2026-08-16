@extends('layouts.dashboard')

@section('title', 'Record Teacher Attendance')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Record Teacher Attendance</h1>
            <p class="text-gray-600">Create a new teacher attendance record</p>
        </div>
        <a href="{{ route('teacher-attendance.index') }}" 
           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('teacher-attendance.store') }}" class="space-y-6">
            @csrf

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
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                        <span class="text-sm text-gray-500 font-normal">(Auto-filled)</span>
                    </label>
                    <input type="date" id="date" name="date" value="{{ old('date', $selectedDate) }}" required readonly
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
                        <option value="present" {{ old('status') === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ old('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ old('status') === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="on_leave" {{ old('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time In -->
                <div>
                    <label for="time_in" class="block text-sm font-medium text-gray-700 mb-2">
                        Time In
                        <span class="text-sm text-gray-500 font-normal">(Will be set to submission time)</span>
                    </label>
                    <input type="time" id="time_in" name="time_in" value="{{ old('time_in', now()->format('H:i')) }}" readonly
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
                    <input type="time" id="time_out" name="time_out" value="{{ old('time_out', '') }}"
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
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('teacher-attendance.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Record Attendance
                </button>
            </div>
        </form>
    </div>

    <!-- Current Time Display -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-green-900 mb-2">
            <i class="fas fa-clock mr-1"></i>Current Time
        </h3>
        <p class="text-lg font-mono text-green-800" id="current-time">
            {{ now()->format('g:i:s A') }}
        </p>
        <p class="text-sm text-green-700 mt-1">This time will be recorded when you submit the form</p>
        <p class="text-xs text-green-600 mt-1">Timezone: West Africa Time (WAT) - UTC+1</p>
    </div>

    <!-- Help Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>Information
        </h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Date is automatically set to today's date and cannot be changed</li>
            <li>• Time In is automatically set to the exact time when you submit this form (WAT timezone)</li>
            <li>• Time Out is optional and can be set manually when the teacher checks out</li>
            <li>• Reason is required for Absent, Late, or On Leave status</li>
            <li>• Only one attendance record per teacher per date is allowed</li>
            <li>• All times are recorded in West Africa Time (WAT) - UTC+1</li>
            @if($currentRole !== 'super_admin')
            <li>• You can only record attendance for teachers in your branch</li>
            @endif
        </ul>
    </div>
</div>

<script>
    // Auto-fill time fields based on status
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const reasonField = document.getElementById('reason');
        
        // Clear reason field when status changes
        if (status === 'present') {
            reasonField.value = '';
        }
    });

    // Update current time display every second
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: true, 
            hour: 'numeric', 
            minute: '2-digit', 
            second: '2-digit',
            timeZone: 'Africa/Lagos' // West Africa Time (WAT) - UTC+1
        });
        document.getElementById('current-time').textContent = timeString;
    }
    
    // Update time every second
    setInterval(updateCurrentTime, 1000);
    updateCurrentTime(); // Initial call

    // Update time_in field with current time when form is submitted
    document.querySelector('form').addEventListener('submit', function(e) {
        const now = new Date();
        // Convert to West Africa Time (WAT) - UTC+1
        const watTime = new Date(now.toLocaleString("en-US", {timeZone: "Africa/Lagos"}));
        const currentTime = watTime.getHours().toString().padStart(2, '0') + ':' + watTime.getMinutes().toString().padStart(2, '0');
        
        // Update time_in field with current time
        document.getElementById('time_in').value = currentTime;
        
        // Note: time_out is now user-editable and will not be automatically set
    });
</script>
@endsection

