@extends('layouts.dashboard')

@section('title', ucfirst($status) . ' Lesson Plans')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ ucfirst($status) }} Lesson Plans</h1>
            <p class="mt-2 text-gray-600">View your {{ $status }} lesson plans</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Drafts</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['draft'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Submitted</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['submitted'] }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <a href="{{ route('lesson-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Lesson Plan
            </a>
        </div>

        <!-- Status Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('lesson-plans.index') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('lesson-plans.index') && !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All
                </a>
                <a href="{{ route('lesson-plans.by-status', 'draft') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'draft' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Drafts
                </a>
                <a href="{{ route('lesson-plans.by-status', 'submitted') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'submitted' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Submitted
                </a>
                <a href="{{ route('lesson-plans.by-status', 'approved') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved
                </a>
                <a href="{{ route('lesson-plans.by-status', 'rejected') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ $status === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Rejected
                </a>
            </nav>
        </div>

        <!-- Lesson Plans List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if($lessonPlans->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($lessonPlans as $lessonPlan)
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 truncate">
                                            <a href="{{ route('lesson-plans.show', $lessonPlan) }}" class="hover:text-blue-600">
                                                {{ $lessonPlan->lesson_title }}
                                            </a>
                                        </h3>
                                        <div class="mt-1 flex items-center text-sm text-gray-500 space-x-4">
                                            <span>{{ $lessonPlan->subject_topic }}</span>
                                            <span>•</span>
                                            <span>{{ $lessonPlan->class_grade_level }}</span>
                                            <span>•</span>
                                            <span>{{ $lessonPlan->formatted_lesson_date }}</span>
                                            <span>•</span>
                                            <span>{{ $lessonPlan->duration }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex items-center space-x-3">
                                        {!! $lessonPlan->status_badge !!}
                                        <div class="text-sm text-gray-500">
                                            {{ $lessonPlan->formatted_submitted_at }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex items-center space-x-2">
                                <a href="{{ route('lesson-plans.show', $lessonPlan) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium cursor-pointer">
                                    View
                                </a>
                                @if($lessonPlan->canBeEdited())
                                <a href="{{ route('lesson-plans.edit', $lessonPlan) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium cursor-pointer">
                                    Edit
                                </a>
                                @endif
                                @if($lessonPlan->canBeSubmitted())
                                <form action="{{ route('lesson-plans.submit', $lessonPlan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-medium cursor-pointer" onclick="return confirm('Are you sure you want to submit this lesson plan for review?')">
                                        Submit
                                    </button>
                                </form>
                                @endif
                                @if($lessonPlan->isDraft())
                                <form action="{{ route('lesson-plans.destroy', $lessonPlan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium cursor-pointer" onclick="return confirm('Are you sure you want to delete this lesson plan?')">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $status }} lesson plans found</h3>
                    <p class="mt-1 text-sm text-gray-500">You don't have any {{ $status }} lesson plans at the moment.</p>
                    <div class="mt-6">
                        <a href="{{ route('lesson-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Lesson Plan
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($lessonPlans->hasPages())
            <div class="mt-6">
                {{ $lessonPlans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

