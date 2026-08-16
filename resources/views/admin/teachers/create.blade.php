@extends('layouts.dashboard')

@section('title', 'Add New Teacher')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Teacher</h1>
                    <p class="text-gray-600 mt-2">Create a new teacher account</p>
                </div>
                <a href="{{ route('admin.teachers.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Back to Teachers
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('admin.teachers.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Branch -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                        <select name="branch_id" 
                                id="branch_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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

                    <!-- Qualification -->
                    <div>
                        <label for="qualification" class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                        <input type="text" 
                               name="qualification" 
                               id="qualification" 
                               value="{{ old('qualification') }}"
                               placeholder="e.g., Bachelor's Degree, Master's Degree"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('qualification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <input type="text" 
                               name="specialization" 
                               id="specialization" 
                               value="{{ old('specialization') }}"
                               placeholder="e.g., Mathematics, Science, English"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hire Date -->
                    <div class="md:col-span-2">
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                        <input type="date" 
                               name="hire_date" 
                               id="hire_date" 
                               value="{{ old('hire_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Subject Assignment -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Subject Assignment</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="subject_ids" class="block text-sm font-medium text-gray-700 mb-2">Assign Subjects</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                                @forelse($subjects as $subject)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="subject_ids[]" 
                                               value="{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                        <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                                        @if($subject->code)
                                            <span class="ml-2 text-xs text-gray-500">({{ $subject->code }})</span>
                                        @endif
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No subjects available. Please create subjects first.</p>
                                @endforelse
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Select which subjects this teacher will teach</p>
                            @error('subject_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('subject_ids.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Class Assignment -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Class Assignment</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="class_ids" class="block text-sm font-medium text-gray-700 mb-2">Assign Classes</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
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
                                    <p class="text-sm text-gray-500">No classes available. Please create classes first.</p>
                                @endforelse
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Select which classes this teacher will teach</p>
                            @error('class_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('class_ids.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Note about default password -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Default Password</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>The teacher will be created with a default password: <strong>password123</strong></p>
                                <p class="mt-1">They should change this password upon their first login.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Create Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

