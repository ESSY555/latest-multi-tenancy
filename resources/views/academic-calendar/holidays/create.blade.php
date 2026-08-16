@extends('layouts.dashboard')

@section('title', 'Add Academic Holiday')

@section('dashboard')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Academic Holiday</h1>
            <p class="text-gray-600 mt-2">Define a school holiday or break for {{ $academicYear->name }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('academic-calendar.holidays.store', $academicYear->id) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Holiday Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Holiday/Break Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., Summer Break, National Day">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Holiday Type -->
                    <div>
                        <label for="holiday_type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="holiday_type" id="holiday_type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="holiday" {{ old('holiday_type') == 'holiday' ? 'selected' : '' }}>Public Holiday</option>
                            <option value="break" {{ old('holiday_type') == 'break' ? 'selected' : '' }}>Term Break</option>
                            <option value="vacation" {{ old('holiday_type') == 'vacation' ? 'selected' : '' }}>Vacation</option>
                            <option value="special" {{ old('holiday_type') == 'special' ? 'selected' : '' }}>Special Leave</option>
                            <option value="academic" {{ old('holiday_type') == 'academic' ? 'selected' : '' }}>Non-Instructional Academic Day</option>
                        </select>
                        @error('holiday_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">Highlight Color</label>
                            <input type="color" name="color" id="color" value="{{ old('color', '#dc2626') }}"
                                   class="mt-1 block w-16 h-10 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Public Holiday Toggle -->
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="is_public_holiday" id="is_public_holiday" value="1" {{ old('is_public_holiday', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_public_holiday" class="ml-2 block text-sm text-gray-900">
                                This is a mandatory public holiday
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('academic-calendar.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Add Holiday
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

