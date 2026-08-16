@extends('layouts.dashboard')

@section('title', 'Create Subject')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Subject</h1>
                    <p class="text-gray-600 mt-2">Add a new subject and assign it to classes</p>
                </div>
                <a href="{{ route('subjects.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                    Back to Subjects
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('subjects.store') }}" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    @if(auth()->user()->is_super_admin)
                    <!-- Branch Selection (Super Admin Only) -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                        <select name="branch_id" 
                                id="branch_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                onchange="loadClassesForBranch()"
                                required>
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
                    @endif

                    <!-- Subject Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Subject Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}" 
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
                               value="{{ old('code') }}" 
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
                        <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3" id="classes-container">
                            @forelse($classes as $class)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="class_ids[]" 
                                           value="{{ $class->id }}"
                                           {{ in_array($class->id, old('class_ids', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                                    <span class="ml-2 text-sm text-gray-700">{{ $class->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500" id="no-classes-msg">
                                    @if(auth()->user()->is_super_admin)
                                        Please select a branch first to see available classes.
                                    @else
                                        No classes available. Please create classes first.
                                    @endif
                                </p>
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
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('subjects.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium cursor-pointer">
                        Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->is_super_admin)
<script>
function loadClassesForBranch() {
    const branchId = document.getElementById('branch_id').value;
    const classesContainer = document.getElementById('classes-container');
    
    if (!branchId) {
        classesContainer.innerHTML = '<p class="text-sm text-gray-500">Please select a branch to see available classes.</p>';
        return;
    }
    
    // Show loading state
    classesContainer.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div><span class="ml-2 text-green-600">Loading classes...</span></div>';
    
    // Fetch classes for the selected branch
    fetch(`/api/branches/${branchId}/classes`)
    .then(response => response.json())
    .then(data => {
        if (data.classes && data.classes.length > 0) {
            let html = '';
            data.classes.forEach(cls => {
                // Check if this class was previously selected (from old() data)
                const wasSelected = false; // This is fresh load, so nothing was selected from old()
                html += `
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="class_ids[]" 
                               value="${cls.id}"
                               ${wasSelected ? 'checked' : ''}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                        <span class="ml-2 text-sm text-gray-700">${cls.name}</span>
                    </label>
                `;
            });
            classesContainer.innerHTML = html;
        } else {
            classesContainer.innerHTML = '<p class="text-sm text-gray-500">No classes available for this branch.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        classesContainer.innerHTML = '<p class="text-sm text-red-600">Error loading classes. Please try again.</p>';
    });
}
</script>
@endif

@endsection



