@extends('layouts.dashboard')

@section('title', 'Add Student Remark')

@section('dashboard')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Student Remark</h1>
            <p class="mt-2 text-gray-600">Add behavioral or academic remark for a student in {{ $formTeacher->schoolClass->name }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('form-teacher.remarks.store') }}" class="space-y-6 p-6">
                @csrf
                
                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                    <select id="student_id" name="student_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Select a student</option>
                        @foreach($students as $enrollment)
                            <option value="{{ $enrollment->student->id }}" {{ old('student_id') == $enrollment->student->id ? 'selected' : '' }}>
                                {{ $enrollment->student->name }} ({{ $enrollment->student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remark Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Remark Type</label>
                    <select id="type" name="type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Select type</option>
                        <option value="academic" {{ old('type') == 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="behavioral" {{ old('type') == 'behavioral' ? 'selected' : '' }}>Behavioral</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Brief title for the remark">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="4" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Detailed description of the remark">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Severity -->
                <div>
                    <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
                    <select id="severity" name="severity" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="neutral" {{ old('severity') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                        <option value="positive" {{ old('severity') == 'positive' ? 'selected' : '' }}>Positive</option>
                        <option value="concern" {{ old('severity') == 'concern' ? 'selected' : '' }}>Concern</option>
                        <option value="serious" {{ old('severity') == 'serious' ? 'selected' : '' }}>Serious</option>
                    </select>
                    @error('severity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Private Remark -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_private" id="is_private" value="1" {{ old('is_private') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_private" class="ml-2 block text-sm text-gray-900">
                        Make this remark private (not visible to parents)
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('form-teacher.remarks') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Add Remark
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

