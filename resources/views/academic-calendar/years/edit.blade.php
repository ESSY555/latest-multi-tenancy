@extends('layouts.dashboard')

@section('title', 'Edit Academic Year')

@section('dashboard')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Academic Year</h1>
            <p class="text-gray-600 mt-2">Update academic year details: {{ $academicYear->name }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('academic-calendar.years.update', $academicYear->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Year Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Academic Year Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $academicYear->name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., 2024-2025 Academic Year">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Brief description of the academic year">{{ old('description', $academicYear->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Set as active academic year
                        </label>
                    </div>
                    <p class="text-sm text-gray-500">Only one academic year can be active at a time. Setting this as active will deactivate any other active year.</p>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('academic-calendar.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Academic Year
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

