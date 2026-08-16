@extends('layouts.dashboard')

@section('title', 'Class Announcements')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Class Announcements</h1>
                <p class="mt-2 text-gray-600">Important updates and announcements from your form teacher</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg px-6 py-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-bullhorn mr-3 text-2xl"></i>
                    <div>
                        <h2 class="text-lg font-bold">Your Class</h2>
                        <p class="text-sm opacity-90">{{ $currentEnrollment->schoolClass->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Announcements</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $announcements->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Announcements</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('expiry_date', '>=', now())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">High Priority</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('priority', 'high')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Urgent</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $announcements->where('priority', 'urgent')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Announcements</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest updates from your form teacher</p>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($announcements as $announcement)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h4 class="text-lg font-medium text-gray-900">{{ $announcement->title }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $announcement->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                   ($announcement->priority === 'high' ? 'bg-yellow-100 text-yellow-800' :
                                   ($announcement->priority === 'medium' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                        </div>
                        
                        <div class="prose max-w-none text-gray-600 mb-4">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>

                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                <span>{{ $announcement->formTeacher->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>{{ $announcement->created_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                            @if($announcement->expiry_date)
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                <span>Expires: {{ $announcement->expiry_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                    <i class="fas fa-bullhorn text-gray-400 text-xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                <p class="mt-1 text-sm text-gray-500">No announcements have been posted for your class yet.</p>
            </div>
            @endforelse
        </div>

        @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
    </div>
</div>
@endsection

