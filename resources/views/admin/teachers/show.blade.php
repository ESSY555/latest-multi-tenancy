@extends('layouts.dashboard')

@section('title', 'Teacher Profile - ' . $teacher->name)

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $teacher->name }}</h1>
                    <p class="text-gray-600 mt-2">Teacher Profile — {{ $branch->name ?? 'No branch' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Edit Teacher
                    </a>
                    <a href="{{ route('admin.teachers.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Back to Teachers
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Teacher Information Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                        @if($teacher->profile_photo)
                            <img src="{{ asset('uploads/profile-photos/' . $teacher->profile_photo) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-blue-600 font-semibold text-3xl">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $teacher->name }}</h3>
                    <p class="text-gray-600">Teacher · ID {{ $teacher->id }}</p>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <p class="text-gray-900">{{ $teacher->email }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Phone:</span>
                        <p class="text-gray-900">{{ $teacher->phone ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Address:</span>
                        <p class="text-gray-900">{{ $teacher->address ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Branch:</span>
                        <p class="text-gray-900">{{ $branch->name ?? 'Not assigned' }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Qualification:</span>
                        <p class="text-gray-900">{{ $teacher->qualification ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Specialization:</span>
                        <p class="text-gray-900">{{ $teacher->specialization ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Hire Date:</span>
                        <p class="text-gray-900">{{ $teacher->hire_date ? $teacher->hire_date->format('M j, Y') : 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-sm font-medium text-gray-600">Classes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-sm font-medium text-gray-600">Subjects</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-sm font-medium text-gray-600">Lesson Plans</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_lesson_plans'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-sm font-medium text-gray-600">Assignments</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_assignments'] }}</p>
            </div>
        </div>

        <!-- Assignments -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subjects Taught</h3>
                @if($teacher->subjects->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($teacher->subjects as $subject)
                            <span class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">{{ $subject->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No subjects assigned yet.</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Classes Taught</h3>
                @if($teacher->teachingClasses->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($teacher->teachingClasses as $class)
                            <span class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">{{ $class->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No classes assigned yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
