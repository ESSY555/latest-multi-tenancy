@extends('layouts.dashboard')

@section('title', 'Teacher Reports Review')

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Teacher Reports Review</h1>
            <p class="text-gray-600">Review and manage teacher reports for {{ $teacherReports->isNotEmpty() ? ($teacherReports->first()->branch->name ?? 'your branch') : 'your branch' }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Reports</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
                <a href="{{ route('teacher-reports.index') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('teacher-reports.index') && !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Reports
                </a>
                <a href="{{ route('teacher-reports.by-status', 'submitted') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'submitted' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending Review
                </a>
                <a href="{{ route('teacher-reports.by-status', 'approved') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved
                </a>
                <a href="{{ route('teacher-reports.by-status', 'rejected') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Rejected
                </a>
            </nav>
        </div>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-lg shadow-md">
        @if($teacherReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topics</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($teacherReports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ substr($report->teacher->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->teacher->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $report->teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->formatted_report_date }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if(is_array($report->classes_taught))
                                        @php
                                            $classNames = \App\Models\SchoolClass::whereIn('id', $report->classes_taught)->pluck('name')->toArray();
                                        @endphp
                                        {{ implode(', ', $classNames) }}
                                    @else
                                        {{ $report->classes_taught }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if(is_array($report->subjects_taught))
                                        @php
                                            $subjectNames = \App\Models\Subject::whereIn('id', $report->subjects_taught)->pluck('name')->toArray();
                                        @endphp
                                        {{ implode(', ', $subjectNames) }}
                                    @else
                                        {{ $report->subjects_taught }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $report->topics_covered }}">
                                        {{ Str::limit($report->topics_covered, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $report->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('teacher-reports.show', $report) }}" 
                                           class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                            View
                                        </a>
                                        @if($report->canBeReviewed() && Auth::user()->id !== $report->teacher_id)
                                            <form action="{{ route('teacher-reports.approve', $report) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 cursor-pointer"
                                                        onclick="return confirm('Are you sure you want to approve this report?')">
                                                    Approve
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-900 cursor-pointer"
                                                    onclick="showRejectModal({{ $report->id }})">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $teacherReports->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no teacher reports to review at this time.</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Teacher Report</h3>
            <form id="rejectForm" method="POST">
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
function showRejectModal(reportId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/teacher-reports/${reportId}/reject`;
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
@endsection

