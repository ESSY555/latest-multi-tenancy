@extends('layouts.dashboard')

@section('title', $lessonPlan->lesson_title)

@section('dashboard')
@include('lesson-plans.partials.official-format-head')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Actions -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $lessonPlan->lesson_title }}</h1>
                <p class="mt-2 text-gray-600">{{ $lessonPlan->subject_topic }} • {{ $lessonPlan->class_grade_level }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
                @if($lessonPlan->canBeEdited())
                    <a href="{{ route('lesson-plans.edit', $lessonPlan) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Lesson Plan
                    </a>
                @endif

                @if($lessonPlan->canBeSubmitted())
                    <form action="{{ route('lesson-plans.submit', $lessonPlan) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors cursor-pointer" onclick="return confirm('Are you sure you want to submit this lesson plan for review?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit for Review
                        </button>
                    </form>
                @endif

                @if(in_array(session('current_role'), ['admin', 'super_admin']) && $lessonPlan->canBeReviewed())
                    <button onclick="showApproveModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approve
                    </button>

                    <button onclick="showRejectModal()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Reject
                    </button>
                @endif

                <a href="{{ route('lesson-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="mb-8">
            {!! $lessonPlan->status_badge !!}
            <div class="mt-2 text-sm text-gray-600">
                @if($lessonPlan->status === 'submitted')
                    Submitted on {{ $lessonPlan->formatted_submitted_at }}
                @elseif($lessonPlan->status === 'approved')
                    Approved on {{ $lessonPlan->formatted_reviewed_at }} by {{ $lessonPlan->reviewer->name ?? 'Admin' }}
                @elseif($lessonPlan->status === 'rejected')
                    Rejected on {{ $lessonPlan->formatted_reviewed_at }} by {{ $lessonPlan->reviewer->name ?? 'Admin' }}
                @endif
            </div>
        </div>

        <!-- Rejection Reason (if rejected) -->
        @if($lessonPlan->status === 'rejected' && $lessonPlan->rejection_reason)
            <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Rejection Reason</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ $lessonPlan->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-4 no-print">
            <button type="button" onclick="window.print()"
                class="inline-flex items-center px-4 py-2 border border-slate-300 bg-white text-slate-800 text-sm font-medium rounded-md hover:bg-slate-50">
                Print official format
            </button>
        </div>

        <!-- Official Bezaleel lesson plan layout (table grid; scoped styles from official-format-head partial) -->
        <div class="bg-white shadow sm:rounded-lg p-4 sm:p-6 border border-slate-200 official-lesson-print-wrap">
            @include('lesson-plans.partials.official-format', ['lessonPlan' => $lessonPlan])
        </div>

        <div class="mt-6 no-print text-xs text-slate-500">
            <p>Teacher: {{ $lessonPlan->teacher_name }} · Date: {{ $lessonPlan->formatted_lesson_date }}</p>
        </div>

        @if($lessonPlan->attachments && $lessonPlan->attachments->count() > 0)
            <div class="mt-6 bg-white shadow sm:rounded-lg p-4 sm:p-6 no-print">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Attachments</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($lessonPlan->attachments as $attachment)
                        <div class="relative group bg-gray-50 rounded-md overflow-hidden border">
                            <a href="{{ Storage::disk('public')->url($attachment->path) }}" target="_blank" class="block">
                                <img src="{{ Storage::disk('public')->url($attachment->path) }}" alt="Attachment" class="w-full h-40 object-cover">
                            </a>
                            @if($lessonPlan->isDraft() && $lessonPlan->teacher_id === (auth()->id() ?? 0))
                                <form action="{{ route('lesson-plans.attachments.destroy', [$lessonPlan, $attachment]) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove this attachment?')" class="px-2 py-1 text-xs bg-red-600 text-white rounded cursor-pointer">Remove</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
@if(in_array(session('current_role'), ['admin', 'super_admin']) && $lessonPlan->canBeReviewed())
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Approve Lesson Plan</h3>
            <div class="mt-4 text-sm text-gray-600">
                <p class="mb-2"><strong>{{ $lessonPlan->lesson_title }}</strong></p>
                <p class="mb-2">Teacher: {{ $lessonPlan->teacher->name ?? 'Unknown Teacher' }}</p>
                <p class="mb-2">Subject: {{ $lessonPlan->subject_topic }}</p>
                <p class="mb-4">Class: {{ $lessonPlan->class_grade_level }}</p>
                <p class="text-gray-700">Are you sure you want to approve this lesson plan?</p>
                <p class="text-xs text-gray-500 mt-2">The teacher will be notified of the approval.</p>
            </div>
            <div class="flex justify-center space-x-4 mt-6">
                <button onclick="hideApproveModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">
                    Cancel
                </button>
                <form action="{{ route('lesson-plans.approve', $lessonPlan) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 cursor-pointer transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Reject Lesson Plan</h3>
            <div class="mt-4 text-sm text-gray-600 text-center">
                <p class="mb-2"><strong>{{ $lessonPlan->lesson_title }}</strong></p>
                <p class="mb-2">Teacher: {{ $lessonPlan->teacher->name ?? 'Unknown Teacher' }}</p>
                <p class="mb-4">Subject: {{ $lessonPlan->subject_topic }}</p>
            </div>
            <form action="{{ route('lesson-plans.reject', $lessonPlan) }}" method="POST" class="mt-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                              placeholder="Please provide a detailed reason for rejection...&#10;&#10;Example:&#10;- Learning objectives are not clearly defined&#10;- Assessment methods need improvement&#10;- Lesson activities lack engagement&#10;- Materials/resources are insufficient"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters required. The teacher will receive this feedback.</p>
                </div>
                <div class="flex justify-center space-x-4 mt-6">
                    <button type="button" onclick="hideRejectModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 cursor-pointer transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function showApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (event.target === approveModal) {
        hideApproveModal();
    }
    if (event.target === rejectModal) {
        hideRejectModal();
    }
}
</script>
@endsection

