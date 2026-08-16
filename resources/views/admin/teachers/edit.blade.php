@extends('layouts.dashboard')

@section('title', 'Edit Teacher - ' . $teacher->name)

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Teacher</h1>
                    <p class="text-gray-600 mt-2">Update teacher information for {{ $teacher->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.teachers.show', $teacher) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View Profile
                    </a>
                    <a href="{{ route('admin.teachers.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Back to Teachers
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Personal / Account Details -->
                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $teacher->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $teacher->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $teacher->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" id="address"
                               value="{{ old('address', $teacher->address) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Qualification -->
                    <div>
                        <label for="qualification" class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                        <input type="text" name="qualification" id="qualification"
                               value="{{ old('qualification', $teacher->qualification) }}"
                               placeholder="e.g., B.Sc, M.Ed"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('qualification')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <input type="text" name="specialization" id="specialization"
                               value="{{ old('specialization', $teacher->specialization) }}"
                               placeholder="e.g., Mathematics"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('specialization')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Hire Date -->
                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                        <input type="date" name="hire_date" id="hire_date"
                               value="{{ old('hire_date', optional($teacher->hire_date)->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('hire_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Branch (super admin can reassign; branch admin sees it read-only) -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                        <select name="branch_id" id="branch_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ auth()->user()->is_super_admin ? '' : 'bg-gray-100' }}"
                                {{ auth()->user()->is_super_admin ? '' : 'disabled' }}>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id', $teacherBranch->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Password -->
                <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4 border-b border-gray-200 pb-2">Change Password</h3>
                <p class="text-sm text-gray-500 mb-4">Leave these fields blank to keep the current password.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               placeholder="At least 6 characters"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Assignments -->
                <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4 border-b border-gray-200 pb-2">Assignments</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subjects -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Subjects</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-1">
                            @forelse($subjects as $subject)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ in_array($subject->id, old('subject_ids', $assignedSubjectIds)) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No subjects available for this branch.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Classes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Classes</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-1">
                            @forelse($classes as $class)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="class_ids[]" value="{{ $class->id }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ in_array($class->id, old('class_ids', $assignedClassIds)) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $class->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No classes available for this branch.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Update Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
