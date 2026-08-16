@extends('layouts.dashboard')

@section('dashboard')
<div class="py-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Assignments</h3>
            <p class="text-gray-600">Your current and upcoming assignments</p>
        </div>

        <div class="space-y-4">
            @forelse($assignments as $assignment)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">{{ $assignment->title }}</h4>
                        <p class="text-gray-600 mt-1">{{ $assignment->description ?? 'No description available' }}</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $assignment->schoolClass->name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $assignment->schoolClass->name }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        @if($assignment->due_date)
                            @if($assignment->due_date->isPast())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Overdue
                                </span>
                            @elseif($assignment->due_date->diffInDays(now()) <= 3)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Due Soon
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">Due: {{ $assignment->due_date->format('M d, Y') }}</p>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                No Due Date
                            </span>
                        @endif
                    </div>
                </div>
                @php
                    $mySubmission = $assignment->submissions->first();
                @endphp
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm">
                        @if($mySubmission)
                            <div class="space-y-1">
                                <div>Status: <span class="font-medium">{{ ucfirst($mySubmission->status) }}</span></div>
                                @if($mySubmission->grade)
                                    <div>Grade: <span class="font-medium">{{ $mySubmission->grade }}</span></div>
                                @endif
                                @if($mySubmission->remarks)
                                    <div>Feedback: <span class="font-medium">{{ $mySubmission->remarks }}</span></div>
                                @endif
                                <div class="text-gray-500">Submitted: {{ optional($mySubmission->submitted_at)->format('M d, Y H:i') }}</div>
                            </div>
                        @else
                            <div class="text-gray-500">No submission yet.</div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        @if(!$mySubmission || ($mySubmission && !$mySubmission->isFinalized()))
                            <a href="{{ route('assignments.submit', $assignment) }}" class="px-3 py-2 bg-blue-600 text-white rounded">
                                {{ $mySubmission ? 'Resubmit' : 'Submit' }}
                            </a>
                        @endif
                        <a href="{{ route('assignments.show', $assignment) }}" class="px-3 py-2 border rounded">Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any assignments at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection



