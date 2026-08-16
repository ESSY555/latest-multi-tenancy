@extends('layouts.dashboard')

@section('title', 'Result Management')

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Result Management</h1>
                        <p class="text-gray-600 mt-2">Manage student result sheets and academic performance</p>                    @if($currentAcademicYear)
                            <p class="text-sm text-green-600 mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i>Current Academic Year: <strong>{{ $currentAcademicYear->name }}</strong>
                            </p>
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('result.create') }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                            <i class="fas fa-plus mr-2"></i>Add Result
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">Academic Session</label>
                            <select name="academic_year_id" id="academic_year_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                                @foreach($allSessions as $session)
                                    <option value="{{ $session->id }}" {{ $selectedYearId == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} {{ $session->is_active ? '(Active)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                            <select name="class_id" id="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter by subject hidden for admins, as we group by sheet -->
                        @if(!auth()->user()->hasRole('admin') && !auth()->user()->is_super_admin)
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select name="subject_id" id="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label for="term_id" class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                            <select name="term_id" id="term_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">All Terms</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }} - {{ $term->academicYear->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>

                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('result.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Student Result Sheets ({{ $results->total() }} total sheets)</h3>
                        @if($results->count() > 0)
                        <div class="flex gap-2" id="bulk-actions" style="display: none;">
                            <button onclick="approveSelected()" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                                <i class="fas fa-check mr-1"></i>Approve Selected Sheets
                            </button>
                            <button onclick="disapproveSelected()" class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors cursor-pointer">
                                <i class="fas fa-times mr-1"></i>Disapprove Selected Sheets
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                @if($results->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects Completed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results as $result)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="result-checkbox" data-student-id="{{ $result->student_id }}" data-term-id="{{ $result->term_id }}" onchange="updateBulkActions()" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-green-800">
                                                            {{ substr($result->student->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $result->student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $result->student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $result->schoolClass->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $result->academicTerm->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            <span class="px-2 py-1 bg-gray-100 rounded-full font-semibold">{{ $result->total_subjects }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ number_format($result->total_score, 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ number_format($result->average_score, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($result->is_approved)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm font-medium">
                                            <a href="{{ route('result.student-sheet', ['studentId' => $result->student_id, 'termId' => $result->term_id]) }}"
                                                class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-600 hover:text-white transition-all duration-200 font-semibold text-xs border border-blue-200 hover:border-blue-600 shadow-sm group">
                                                <i class="fas fa-file-invoice group-hover:scale-110 transition-transform"></i> 
                                                <span>View Sheet</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $results->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
                            <p class="text-gray-500">No results match your current filters.</p>
                            <a href="{{ route('result.create') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                                <i class="fas fa-plus mr-2"></i>Add First Result
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.result-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateBulkActions();
    }

    function updateBulkActions() {
        const selectedCount = document.querySelectorAll('.result-checkbox:checked').length;
        const bulkActions = document.getElementById('bulk-actions');
        if (bulkActions) {
            bulkActions.style.display = selectedCount > 0 ? 'flex' : 'none';
        }
    }

    function getSelectedSheets() {
        return Array.from(document.querySelectorAll('.result-checkbox:checked'))
            .map(cb => ({
                student_id: cb.getAttribute('data-student-id'),
                term_id: cb.getAttribute('data-term-id')
            }));
    }

    function approveSelected() {
        const sheets = getSelectedSheets();
        if (sheets.length === 0) {
            alert('Please select at least one sheet to approve');
            return;
        }

        if (!confirm(`Are you sure you want to approve ${sheets.length} student sheet(s)?`)) {
            return;
        }

        performBulkApproveSheets(sheets);
    }

    function disapproveSelected() {
        const sheets = getSelectedSheets();
        if (sheets.length === 0) {
            alert('Please select at least one sheet to disapprove');
            return;
        }

        if (!confirm(`Are you sure you want to disapprove ${sheets.length} student sheet(s)?`)) {
            return;
        }

        performBulkDisapproveSheets(sheets);
    }

    function performBulkApproveSheets(sheets) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("result.bulk-approve-sheets") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ sheets: sheets })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while approving sheets');
        });
    }

    function performBulkDisapproveSheets(sheets) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("result.bulk-disapprove-sheets") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ sheets: sheets })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while disapproving sheets');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateBulkActions();
    });
    </script>
@endsection

