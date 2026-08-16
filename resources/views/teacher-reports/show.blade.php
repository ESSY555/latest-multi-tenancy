@extends('layouts.dashboard')

@section('title', 'Teacher Report Details')

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Teacher Report Details</h1>
            <p class="text-gray-600">Report for {{ $teacherReport->formatted_report_date }}</p>
        </div>
        <div class="flex space-x-3">
            @if($teacherReport->canBeEdited() && Auth::user()->id === $teacherReport->teacher_id)
                <a href="{{ route('teacher-reports.edit', $teacherReport) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200 cursor-pointer">
                    Edit Report
                </a>
            @endif
            @if($teacherReport->canBeSubmitted() && Auth::user()->id === $teacherReport->teacher_id)
                <form action="{{ route('teacher-reports.submit', $teacherReport) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200 cursor-pointer"
                            onclick="return confirm('Are you sure you want to submit this report?')">
                        Submit for Review
                    </button>
                </form>
            @endif
            @if($teacherReport->canBeReviewed() && in_array(session('current_role'), ['admin', 'super_admin']) && Auth::user()->id !== $teacherReport->teacher_id)
                <form action="{{ route('teacher-reports.approve', $teacherReport) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200 cursor-pointer"
                            onclick="return confirm('Are you sure you want to approve this report?')">
                        Approve
                    </button>
                </form>
                <button type="button" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 cursor-pointer"
                        onclick="showRejectModal()">
                    Reject
                </button>
            @endif
            <a href="{{ route('teacher-reports.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="bg-white rounded shadow p-4">
        {!! $teacherReport->status_badge !!}
        @if($teacherReport->status === 'pending')
            <p class="text-sm text-gray-600 mt-2">Submitted on {{ $teacherReport->formatted_submitted_at }} - Waiting for admin review</p>
        @elseif($teacherReport->status === 'approved')
            <p class="text-sm text-gray-600 mt-2">Approved on {{ $teacherReport->formatted_reviewed_at }} by {{ $teacherReport->reviewer->name ?? 'Admin' }}</p>
        @elseif($teacherReport->status === 'rejected')
            <p class="text-sm text-gray-600 mt-2">Rejected on {{ $teacherReport->formatted_reviewed_at }} by {{ $teacherReport->reviewer->name ?? 'Admin' }}</p>
        @endif
    </div>

    <!-- Report Content -->
    <div class="bg-white rounded shadow p-6">
        <!-- Basic Information Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teacher's Name</label>
                    <p class="text-gray-900">{{ $teacherReport->teacher_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Date</label>
                    <p class="text-gray-900">{{ $teacherReport->formatted_report_date }}</p>
                </div>
            </div>
        </div>

        <!-- Class & Subject Details Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Class & Subject Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Classes Taught</label>
                    <p class="text-gray-900">
                        @if(is_array($teacherReport->classes_taught))
                            @php
                                $classNames = \App\Models\SchoolClass::whereIn('id', $teacherReport->classes_taught)->pluck('name')->toArray();
                            @endphp
                            {{ implode(', ', $classNames) }}
                        @else
                            {{ $teacherReport->classes_taught }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjects Taught</label>
                    <p class="text-gray-900">
                        @if(is_array($teacherReport->subjects_taught))
                            @php
                                $subjectNames = \App\Models\Subject::whereIn('id', $teacherReport->subjects_taught)->pluck('name')->toArray();
                            @endphp
                            {{ implode(', ', $subjectNames) }}
                        @else
                            {{ $teacherReport->subjects_taught }}
                        @endif
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Topics Covered</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->topics_covered }}</p>
            </div>
        </div>

        <!-- Lesson & Progress Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Lesson & Progress</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teaching Method Used</label>
                    <p class="text-gray-900">{{ $teacherReport->teaching_method_label }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Participation/Engagement</label>
                    <p class="text-gray-900">{{ $teacherReport->student_participation_label }}</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Objectives Achieved?</label>
                <p class="text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $teacherReport->objectives_achieved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $teacherReport->objectives_achieved ? 'Yes' : 'No' }}
                    </span>
                </p>
                @if($teacherReport->objectives_notes)
                    <p class="text-gray-900 mt-2 whitespace-pre-wrap">{{ $teacherReport->objectives_notes }}</p>
                @endif
            </div>

            @if($teacherReport->participation_notes)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Participation Notes</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->participation_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Assignments & Activities Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Assignments & Activities</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Homework/Assignment Given?</label>
                <p class="text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $teacherReport->homework_assigned ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $teacherReport->homework_assigned ? 'Yes' : 'No' }}
                    </span>
                </p>
                @if($teacherReport->homework_details)
                    <p class="text-gray-900 mt-2 whitespace-pre-wrap">{{ $teacherReport->homework_details }}</p>
                @endif
            </div>

            @if($teacherReport->class_activities)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class Activities Done</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->class_activities }}</p>
                </div>
            @endif
        </div>

        <!-- Challenges & Needs Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Challenges & Needs</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($teacherReport->challenges_faced)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Challenges Faced</label>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->challenges_faced }}</p>
                    </div>
                @endif
                @if($teacherReport->materials_needed)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Materials/Resources Needed</label>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->materials_needed }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Notes Section -->
        @if($teacherReport->additional_notes)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Additional Notes</h2>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $teacherReport->additional_notes }}</p>
            </div>
        @endif

        <!-- Rejection Reason (if rejected) -->
        @if($teacherReport->status === 'rejected' && $teacherReport->rejection_reason)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-red-800 mb-4 border-b border-red-200 pb-2">Rejection Reason</h2>
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <p class="text-red-800 whitespace-pre-wrap">{{ $teacherReport->rejection_reason }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
@if($teacherReport->canBeReviewed())
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Teacher Report</h3>
            <form action="{{ route('teacher-reports.reject', $teacherReport) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea id="rejection_reason" 
                              name="rejection_reason" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide a reason for rejecting this report..."
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 cursor-pointer">
                        Reject Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
}

function hideRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});
</script>
@endif
@endsection

