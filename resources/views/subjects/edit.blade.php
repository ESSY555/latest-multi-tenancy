@extends('layouts.dashboard')

@section('title', 'Edit Subject')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Subject</h1>
                    <p class="text-gray-600 mt-2">Update subject information and class assignments</p>
                </div>
                <a href="{{ route('subjects.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                    Back to Subjects
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('subjects.update', $subject->id) }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Subject Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Subject Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $subject->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., Mathematics, English, Science"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Subject Code</label>
                        <input type="text" 
                               name="code" 
                               id="code"
                               value="{{ old('code', $subject->code) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., MATH, ENG, SCI">
                        <p class="mt-1 text-sm text-gray-500">Optional: Short code for the subject</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div>
                        <label for="class_ids" class="block text-sm font-medium text-gray-700 mb-2">Assign to Classes</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @forelse($classes as $class)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="class_ids[]" 
                                           value="{{ $class->id }}"
                                           {{ in_array($class->id, old('class_ids', $subject->classes->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700">{{ $class->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No classes available. Please create classes first.</p>
                            @endforelse
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Select which classes this subject will be taught in</p>
                        @error('class_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('class_ids.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Assignments Info -->
                    @if($subject->classes->count() > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Current Class Assignments</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($subject->classes as $class)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $class->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('subjects.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium cursor-pointer">
                        Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

