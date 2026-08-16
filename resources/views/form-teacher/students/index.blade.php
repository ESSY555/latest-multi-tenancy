@extends('layouts.dashboard')

@section('title', 'Student Records')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Student Records</h1>
            <p class="mt-2 text-gray-600">Managing {{ $formTeacher->schoolClass->name }} students</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ method_exists($students, 'total') ? $students->total() : $students->count() }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Active Students</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $students->where('student.status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">With Remarks</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $students->where('student.remarks_count', '>', 0)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="studentSearch" placeholder="Search students by name or ID..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-2">
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <select id="remarksFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Students</option>
                        <option value="with_remarks">With Remarks</option>
                        <option value="no_remarks">No Remarks</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Class Students</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Student profiles and records for {{ $formTeacher->schoolClass->name }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500" id="studentCount">{{ method_exists($students, 'count') ? $students->count() : 0 }} students</span>
                    </div>
                </div>
            </div>
            <ul class="divide-y divide-gray-200" id="studentsList">
                @forelse($students as $enrollment)
                <li class="student-item" data-name="{{ strtolower($enrollment->student->name) }}" data-id="{{ strtolower($enrollment->student->student_id ?? '') }}" data-status="{{ $enrollment->student->status }}" data-remarks="{{ $enrollment->student->remarks_count > 0 ? 'with_remarks' : 'no_remarks' }}">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($enrollment->student->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $enrollment->student->name }}</p>
                                        @if($enrollment->student->status === 'active')
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Active
                                            </span>
                                        @else
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Inactive
                                            </span>
                                        @endif
                                        @if($enrollment->student->remarks_count > 0)
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-comment-alt mr-1"></i>{{ $enrollment->student->remarks_count }} remarks
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <p>{{ $enrollment->student->email }}</p>
                                        <span class="mx-2">•</span>
                                        <p>ID: {{ $enrollment->student->student_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-ellipsis-v mr-1"></i>Actions
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                        <div class="py-1">
                                            <a href="{{ route('form-teacher.students.show', $enrollment->student->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-2"></i>View Details
                                            </a>
                                            <a href="{{ route('form-teacher.remarks.create') }}?student_id={{ $enrollment->student->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-comment-alt mr-2"></i>Add Remark
                                            </a>
                                            <a href="{{ route('form-teacher.attendance') }}?student_id={{ $enrollment->student->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-calendar-check mr-2"></i>Mark Attendance
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-file-alt mr-2"></i>View Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('form-teacher.students.show', $enrollment->student->id) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-user mr-1"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li>
                    <div class="px-4 py-8 sm:px-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                        <p class="mt-1 text-sm text-gray-500">No students are currently enrolled in this class.</p>
                    </div>
                </li>
                @endforelse
            </ul>
        </div>

        @if($students instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($students, 'links') && $students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearch');
    const statusFilter = document.getElementById('statusFilter');
    const remarksFilter = document.getElementById('remarksFilter');
    const studentItems = document.querySelectorAll('.student-item');
    const studentCount = document.getElementById('studentCount');

    function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const remarksValue = remarksFilter.value;
        let visibleCount = 0;

        studentItems.forEach(item => {
            const name = item.dataset.name;
            const id = item.dataset.id;
            const status = item.dataset.status;
            const remarks = item.dataset.remarks;

            const matchesSearch = name.includes(searchTerm) || id.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesRemarks = !remarksValue || remarks === remarksValue;

            if (matchesSearch && matchesStatus && matchesRemarks) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        studentCount.textContent = `${visibleCount} students`;
    }

    searchInput.addEventListener('input', filterStudents);
    statusFilter.addEventListener('change', filterStudents);
    remarksFilter.addEventListener('change', filterStudents);
});
</script>
@endsection

