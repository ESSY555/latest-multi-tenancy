@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Form Teacher Assignment</h1>
            <a href="{{ route('admin.form-teacher-assignments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.form-teacher-assignments.update', $formTeacherAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                    <input type="text" id="branch_id" value="{{ $formTeacherAssignment->branch->name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                
                <div class="mb-6">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                    <select id="teacher_id" name="teacher_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $formTeacherAssignment->user_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $formTeacherAssignment->school_class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $formTeacherAssignment->start_date ? $formTeacherAssignment->start_date->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $formTeacherAssignment->end_date ? $formTeacherAssignment->end_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter any additional notes about this assignment...">{{ old('notes', $formTeacherAssignment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $formTeacherAssignment->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active Assignment</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Uncheck to deactivate this form teacher assignment</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.form-teacher-assignments.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Update Assignment
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Assignment Info -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Current Assignment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Teacher:</span>
                    <span class="text-gray-900">{{ $formTeacherAssignment->user->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Class:</span>
                    <span class="text-gray-900">{{ $formTeacherAssignment->schoolClass->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Branch:</span>
                    <span class="text-gray-900">{{ $formTeacherAssignment->branch->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $formTeacherAssignment->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $formTeacherAssignment->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Start Date:</span>
                    <span class="text-gray-900">{{ $formTeacherAssignment->start_date ? $formTeacherAssignment->start_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">End Date:</span>
                    <span class="text-gray-900">{{ $formTeacherAssignment->end_date ? $formTeacherAssignment->end_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Information Panel -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Important Notes</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Only one form teacher can be active per class at a time</li>
                <li>• Deactivating this assignment will remove form teacher access</li>
                <li>• Changing the class will affect the teacher's form teacher responsibilities</li>
                <li>• You can set an end date to automatically deactivate the assignment</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Set minimum date for start_date to today
document.getElementById('start_date').min = new Date().toISOString().split('T')[0];

// Ensure end_date is after start_date
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    if (startDate) {
        endDateInput.min = startDate;
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDate = document.getElementById('start_date').value;
    if (endDate && startDate && endDate <= startDate) {
        alert('End date must be after start date');
        this.value = '';
    }
});
</script>
@endsection

