@extends('layouts.dashboard')

@section('title', 'My Teacher Reports')

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">My Teacher Reports</h1>
            <p class="text-gray-600">Reports with status: <span class="font-semibold text-blue-600">{{ ucfirst($status) }}</span></p>
        </div>
        <a href="{{ route('teacher-reports.create') }}" 
           class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 cursor-pointer">
            Create New Report
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
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
                <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Draft</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['draft'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['submitted'] }}</p>
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
                <a href="{{ route('teacher-reports.by-status', 'draft') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'draft' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Draft
                </a>
                <a href="{{ route('teacher-reports.by-status', 'submitted') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'submitted' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending Review
                </a>
                <a href="{{ route('teacher-reports.by-status', 'approved') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved
                </a>
                <a href="{{ route('teacher-reports.by-status', 'rejected') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
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
                                        @if($report->canBeEdited())
                                            <a href="{{ route('teacher-reports.edit', $report) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 cursor-pointer">
                                                Edit
                                            </a>
                                        @endif
                                        @if($report->canBeSubmitted())
                                            <form action="{{ route('teacher-reports.submit', $report) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 cursor-pointer"
                                                        onclick="return confirm('Are you sure you want to submit this report?')">
                                                    Submit
                                                </button>
                                            </form>
                                        @endif
                                        @if($report->isDraft())
                                            <form action="{{ route('teacher-reports.destroy', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 cursor-pointer"
                                                        onclick="return confirm('Are you sure you want to delete this report?')">
                                                    Delete
                                                </button>
                                            </form>
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $status }} reports found</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any {{ $status }} reports at this time.</p>
                <div class="mt-6">
                    <a href="{{ route('teacher-reports.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 cursor-pointer">
                        Create Report
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

