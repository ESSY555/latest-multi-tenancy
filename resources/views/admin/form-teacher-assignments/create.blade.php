@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Assign Form Teacher</h1>
                <a href="{{ route('admin.form-teacher-assignments.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('admin.form-teacher-assignments.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                        <select id="branch_id" name="branch_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                        <select id="teacher_id" name="teacher_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                        <select id="class_id" name="class_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter any additional notes about this assignment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.form-teacher-assignments.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Assign Form Teacher
                        </button>
                    </div>
                    </form>
                    </div>

            <!-- Information Panel -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Important Notes</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Only one form teacher can be active per class at a time</li>
                    <li>• The assigned teacher will have access to form teacher features for the selected class</li>
                    <li>• Form teachers can manage attendance, student remarks, and class announcements</li>
                    <li>• You can set an end date to automatically deactivate the assignment</li>
                </ul>
            </div>
            </div>
            </div>

    <script>
        // Set minimum date for start_date to today
        document.getElementById('start_date').min = new Date().toISOString().split('T')[0];

        // Ensure end_date is after start_date
        document.getElementById('start_date').addEventListener('change', function () {
            const startDate = this.value;
            const endDateInput = document.getElementById('end_date');
            if (startDate) {
                endDateInput.min = startDate;
            }
        });

        document.getElementById('end_date').addEventListener('change', function () {
            const endDate = this.value;
            const startDate = document.getElementById('start_date').value;
            if (endDate && startDate && endDate <= startDate) {
                alert('End date must be after start date');
                this.value = '';
            }
        });
    </script>
@endsection

