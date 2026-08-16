@extends('layouts.dashboard')

@section('title', 'Class Announcements')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Class Announcements</h1>
                    <p class="mt-2 text-gray-600">Communicate with parents of {{ $formTeacher->schoolClass->name }} students</p>
                </div>
                <a href="{{ route('form-teacher.announcements.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Announcement
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.881A10 10 0 008.5 5.5A10 10 0 004.5 5.5A10 10 0 001 5.881A10 10 0 000 7.5A10 10 0 001 9.119A10 10 0 004.5 9.5A10 10 0 008.5 9.5A10 10 0 0011 9.119A10 10 0 0012 7.5A10 10 0 0011 5.881z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Announcements</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $announcements->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Published</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('is_published', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">High Priority</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('priority', 'high')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Urgent</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('priority', 'urgent')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Class Announcements</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">All announcements for {{ $formTeacher->schoolClass->name }} parents</p>
            </div>
            
            <div class="border-t border-gray-200">
                @forelse($announcements as $announcement)
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $announcement->title }}</h4>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $announcement->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                               ($announcement->priority === 'high' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($announcement->priority === 'medium' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($announcement->priority) }}
                                        </span>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $announcement->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $announcement->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($announcement->content, 150) }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14"></path>
                                            </svg>
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        </span>
                                        @if($announcement->expiry_date)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Expires: {{ $announcement->expiry_date->format('M d, Y') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-8 sm:px-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.881A10 10 0 008.5 5.5A10 10 0 004.5 5.5A10 10 0 001 5.881A10 10 0 000 7.5A10 10 0 001 9.119A10 10 0 004.5 9.5A10 10 0 008.5 9.5A10 10 0 0011 9.119A10 10 0 0012 7.5A10 10 0 0011 5.881z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements found</h3>
                    <p class="mt-1 text-sm text-gray-500">No announcements have been created for this class yet.</p>
                </div>
                @endforelse
            </div>
            
            @if($announcements->hasPages())
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                {{ $announcements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

