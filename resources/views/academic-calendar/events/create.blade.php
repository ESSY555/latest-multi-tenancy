@extends('layouts.dashboard')

@section('title', 'Add Academic Event')

@section('dashboard')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Academic Event</h1>
            <p class="text-gray-600 mt-2">Schedule a new event for the {{ $academicYear->name }} academic year</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('academic-calendar.events.store', $academicYear->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., Parent-Teacher Meeting">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div>
                        <label for="event_type" class="block text-sm font-medium text-gray-700">Event Type</label>
                        <select name="event_type" id="event_type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Type</option>
                            <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="administrative" {{ old('event_type') == 'administrative' ? 'selected' : '' }}>Administrative</option>
                            <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="ceremony" {{ old('event_type') == 'ceremony' ? 'selected' : '' }}>Ceremony</option>
                            <option value="sports" {{ old('event_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="cultural" {{ old('event_type') == 'cultural' ? 'selected' : '' }}>Cultural</option>
                            <option value="exam" {{ old('event_type') == 'exam' ? 'selected' : '' }}>Exam Related</option>
                            <option value="assignment" {{ old('event_type') == 'assignment' ? 'selected' : '' }}>Assignment Deadline</option>
                            <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('event_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority" id="priority" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               min="{{ $academicYear->start_date->format('Y-m-d') }}"
                               max="{{ $academicYear->end_date->format('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               min="{{ $academicYear->start_date->format('Y-m-d') }}"
                               max="{{ $academicYear->end_date->format('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div id="start_time_container">
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div id="end_time_container">
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., School Hall, Classroom 1A">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color Picker -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Event Color</label>
                        <input type="color" name="color" id="color" value="{{ old('color', '#4f46e5') }}"
                               class="mt-1 block w-16 h-10 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Toggles -->
                    <div class="flex flex-col space-y-3 justify-center">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_all_day" id="is_all_day" value="1" {{ old('is_all_day') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_all_day" class="ml-2 block text-sm text-gray-900">All Day Event</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_public" class="ml-2 block text-sm text-gray-900">Public Event (Visible to students/parents)</label>
                        </div>
                    </div>

                    <hr class="col-span-2">

                    <!-- Class Multi-select -->
                    <div>
                        <label for="class_ids" class="block text-sm font-medium text-gray-700">Participating Classes (Optional)</label>
                        <select name="class_ids[]" id="class_ids" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-32">
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (is_array(old('class_ids')) && in_array($class->id, old('class_ids'))) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl (Cmd) to select multiple</p>
                    </div>

                    <!-- Subject Multi-select -->
                    <div>
                        <label for="subject_ids" class="block text-sm font-medium text-gray-700">Related Subjects (Optional)</label>
                        <select name="subject_ids[]" id="subject_ids" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-32">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (is_array(old('subject_ids')) && in_array($subject->id, old('subject_ids'))) ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl (Cmd) to select multiple</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="col-span-2 flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('academic-calendar.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Schedule Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isAllDayCheckbox = document.getElementById('is_all_day');
    const startTimeContainer = document.getElementById('start_time_container');
    const endTimeContainer = document.getElementById('end_time_container');

    function toggleTimeFields() {
        if (isAllDayCheckbox.checked) {
            startTimeContainer.style.opacity = '0.5';
            endTimeContainer.style.opacity = '0.5';
            document.getElementById('start_time').disabled = true;
            document.getElementById('end_time').disabled = true;
        } else {
            startTimeContainer.style.opacity = '1';
            endTimeContainer.style.opacity = '1';
            document.getElementById('start_time').disabled = false;
            document.getElementById('end_time').disabled = false;
        }
    }

    isAllDayCheckbox.addEventListener('change', toggleTimeFields);
    toggleTimeFields(); 

    // Date validation
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    startDate.addEventListener('change', function() {
        if (this.value && (!endDate.value || endDate.value < this.value)) {
            endDate.value = this.value;
        }
    });
});
</script>
@endsection

