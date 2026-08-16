@extends('layouts.dashboard')

@section('title', 'Review: ' . $assignment->title)

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Review Submissions</h1>
        <a href="{{ route('assignments.show', $assignment) }}" class="px-3 py-2 border rounded">Back</a>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                <input 
                    type="text" 
                    id="student_name" 
                    name="student_name" 
                    value="{{ $studentName ?? '' }}"
                    placeholder="Enter student name to search..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    oninput="searchStudents(this.value)"
                >
            </div>
            <div class="flex gap-2">
                @if($studentName ?? false)
                    <button onclick="clearSearch()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                @endif
            </div>
        </div>
        
        <div id="search-results" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md" style="display: {{ $studentName ? 'block' : 'none' }};">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-1"></i>
                <span id="search-text">Showing results for: <strong>"{{ $studentName }}"</strong></span>
                <span id="results-count">({{ $submissions->count() }} submission{{ $submissions->count() != 1 ? 's' : '' }} found)</span>
            </p>
        </div>
    </div>

    <div class="bg-white rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr class="text-left">
                    <th class="p-3">Student</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Submitted</th>
                    <th class="p-3">Grade</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody id="submissions-table-body">
                @forelse($submissions as $sub)
                    <tr class="border-t align-top submission-row" data-student-name="{{ strtolower($sub->student->name ?? '') }}">
                        <td class="p-3">{{ $sub->student->name ?? 'Unknown' }}</td>
                        <td class="p-3">{{ ucfirst($sub->status) }}</td>
                        <td class="p-3">{{ optional($sub->submitted_at)->format('Y-m-d H:i') }}</td>
                        <td class="p-3">{{ $sub->grade ?? '-' }}</td>
                        <td class="p-3">
                            <details>
                                <summary class="cursor-pointer text-blue-600">Open</summary>
                                <div class="mt-3 space-y-3">
                                    @if($sub->content)
                                        <div>
                                            <h4 class="font-semibold mb-1">Text</h4>
                                            <div class="prose max-w-none">{!! nl2br(e($sub->content)) !!}</div>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold mb-1">Files</h4>
                                        @if($sub->attachments && $sub->attachments->count())
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach($sub->attachments as $att)
                                                    <li><a href="{{ Storage::disk('public')->url($att->path) }}" target="_blank" class="text-blue-600 hover:underline">{{ $att->original_name }}</a></li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500">No files</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('assignments.submissions.grade', [$assignment, $sub]) }}" class="space-y-2">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-sm">Status</label>
                                                <select name="status" class="w-full border rounded p-2">
                                                    <option value="approved" {{ $sub->status==='approved'?'selected':'' }}>Approved</option>
                                                    <option value="returned" {{ $sub->status==='returned'?'selected':'' }}>Returned</option>
                                                    <option value="graded" {{ $sub->status==='graded'?'selected':'' }}>Graded</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm">Grade</label>
                                                <input type="text" name="grade" value="{{ $sub->grade }}" class="w-full border rounded p-2" />
                                            </div>
                                            <div>
                                                <label class="block text-sm">Allow Resubmit</label>
                                                <input type="checkbox" name="allow_resubmit" value="1" class="h-4 w-4" />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm">Remarks</label>
                                            <textarea name="remarks" rows="3" class="w-full border rounded p-2">{{ $sub->remarks }}</textarea>
                                        </div>
                                        <button class="px-3 py-2 bg-blue-600 text-white rounded">Save</button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr id="no-results-row">
                        <td class="p-3" colspan="5">
                            @if($studentName ?? false)
                                No submissions found for student "{{ $studentName }}".
                            @else
                                No submissions yet.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        let searchTimeout;
        
        function searchStudents(searchTerm) {
            clearTimeout(searchTimeout);
            
            // Add a small delay to avoid too many searches while typing
            searchTimeout = setTimeout(() => {
                const rows = document.querySelectorAll('.submission-row');
                const noResultsRow = document.getElementById('no-results-row');
                const searchResults = document.getElementById('search-results');
                const searchText = document.getElementById('search-text');
                const resultsCount = document.getElementById('results-count');
                
                let visibleCount = 0;
                const searchLower = searchTerm.toLowerCase();
                
                rows.forEach(row => {
                    const studentName = row.getAttribute('data-student-name');
                    if (searchTerm === '' || studentName.includes(searchLower)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide search results summary
                if (searchTerm) {
                    searchResults.style.display = 'block';
                    searchText.innerHTML = `Showing results for: <strong>"${searchTerm}"</strong>`;
                    resultsCount.innerHTML = `(${visibleCount} submission${visibleCount !== 1 ? 's' : ''} found)`;
                } else {
                    searchResults.style.display = 'none';
                }
                
                // Show/hide no results message
                if (noResultsRow) {
                    if (visibleCount === 0 && searchTerm) {
                        noResultsRow.style.display = '';
                        noResultsRow.querySelector('td').textContent = `No submissions found for student "${searchTerm}".`;
                    } else if (visibleCount === 0 && !searchTerm) {
                        noResultsRow.style.display = '';
                        noResultsRow.querySelector('td').textContent = 'No submissions yet.';
                    } else {
                        noResultsRow.style.display = 'none';
                    }
                }
            }, 300); // 300ms delay
        }
        
        function clearSearch() {
            document.getElementById('student_name').value = '';
            searchStudents('');
        }
    </script>
@endsection



