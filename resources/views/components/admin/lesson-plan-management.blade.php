@props(['lessonPlans' => collect(), 'stats' => [], 'currentRole' => 'admin'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">Lesson Plan Management</h2>
                <p class="text-blue-100">Review and manage submitted lesson plans</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-blue-100 text-sm">Pending Review</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-blue-100 text-sm">Total Plans</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="px-6 py-4 bg-gray-50 border-b">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Rejected</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="px-6 py-3 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="{{ route('lesson-plans.index') }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('lesson-plans.index') && !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All Plans
            </a>
            <a href="{{ route('lesson-plans.index', ['status' => 'submitted']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'submitted' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pending Review
                @if(($stats['pending'] ?? 0) > 0)
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $stats['pending'] }}
                    </span>
                @endif
            </a>
            <a href="{{ route('lesson-plans.index', ['status' => 'approved']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Approved
            </a>
            <a href="{{ route('lesson-plans.index', ['status' => 'rejected']) }}" 
               class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Rejected
            </a>
        </nav>
    </div>

    <!-- Lesson Plans List -->
    <div class="divide-y divide-gray-200">
        @forelse($lessonPlans as $lessonPlan)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <a href="{{ route('lesson-plans.show', $lessonPlan) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $lessonPlan->lesson_title }}
                                </a>
                            </h3>
                            <div class="flex items-center space-x-2">
                                {!! $lessonPlan->status_badge !!}
                                @if($lessonPlan->status === 'submitted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Requires Review
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">{{ $lessonPlan->teacher->name ?? 'Unknown Teacher' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>{{ $lessonPlan->subject_topic }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ $lessonPlan->class_grade_level }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $lessonPlan->formatted_lesson_date }}</span>
                            </div>
                        </div>

                        @if($lessonPlan->schoolClass)
                            <div class="text-sm text-gray-500 mb-3">
                                <span class="font-medium">Class:</span> {{ $lessonPlan->schoolClass->name }} - Grade {{ $lessonPlan->schoolClass->grade_level }}
                            </div>
                        @endif

                        <div class="text-sm text-gray-500">
                            @if($lessonPlan->status === 'submitted')
                                <span class="font-medium">Submitted:</span> {{ $lessonPlan->formatted_submitted_at }}
                            @elseif($lessonPlan->status === 'approved' || $lessonPlan->status === 'rejected')
                                <span class="font-medium">Reviewed:</span> {{ $lessonPlan->formatted_reviewed_at }} by {{ $lessonPlan->reviewer->name ?? 'Admin' }}
                            @endif
                        </div>

                        @if($lessonPlan->status === 'rejected' && $lessonPlan->rejection_reason)
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-400 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Rejection Reason</p>
                                        <p class="text-sm text-red-700 mt-1">{{ Str::limit($lessonPlan->rejection_reason, 150) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="ml-6 flex items-center space-x-3">
                        <a href="{{ route('lesson-plans.show', $lessonPlan) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>

                        @if($lessonPlan->canBeReviewed())
                            <a href="{{ route('lesson-plans.show', $lessonPlan) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Review
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No lesson plans found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('status') === 'submitted')
                        No lesson plans are currently pending review.
                    @elseif(request('status') === 'approved')
                        No lesson plans have been approved yet.
                    @elseif(request('status') === 'rejected')
                        No lesson plans have been rejected.
                    @else
                        No lesson plans have been created yet.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lessonPlans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $lessonPlans->links() }}
        </div>
    @endif
</div>

