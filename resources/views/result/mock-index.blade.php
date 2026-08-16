@extends('layouts.dashboard')

@section('title', 'Mock Results')

@section('dashboard')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @php
            $canBulkManage = auth()->user()->hasRole('admin') || auth()->user()->is_super_admin;
        @endphp
        <div class="flex items-end justify-between mb-6">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase">Mock Results</h1>
                <p class="text-sm text-gray-500">View mock result sheets by exam.</p>
            </div>
            <form method="GET" class="flex items-end gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Mock Exam</label>
                    <select name="mock_exam_id" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded text-sm font-semibold">
                        <option value="">All Mock Exams</option>
                        @foreach($mockExams as $exam)
                            <option value="{{ $exam->id }}" {{ (int) $selectedMockExamId === (int) $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Mock Result Sheets ({{ method_exists($sheets, 'total') ? $sheets->total() : $sheets->count() }} total sheets)</h3>
                    @if($canBulkManage && $sheets->count() > 0)
                        <div class="flex gap-2" id="bulk-actions" style="display: none;">
                            <button type="button" onclick="approveSelectedMockSheets()" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                                <i class="fas fa-check mr-1"></i>Approve Selected Sheets
                            </button>
                            <button type="button" onclick="disapproveSelectedMockSheets()" class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors cursor-pointer">
                                <i class="fas fa-times mr-1"></i>Disapprove Selected Sheets
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <table class="w-full border-collapse text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @if($canBulkManage)
                            <th class="p-3 text-left border-b">
                                <input type="checkbox" id="select-all-mock" onchange="toggleSelectAllMockSheets(this)" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                            </th>
                        @endif
                        <th class="p-3 text-left border-b">Student</th>
                        <th class="p-3 text-left border-b">Class</th>
                        <th class="p-3 text-left border-b">Mock Exam</th>
                        <th class="p-3 text-left border-b">Average</th>
                        @if(!auth()->user()->hasRole('student'))
                            <th class="p-3 text-left border-b">Status</th>
                        @endif
                        <th class="p-3 text-left border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sheets as $sheet)
                        <tr class="border-b">
                            @if($canBulkManage)
                                <td class="p-3">
                                    <input type="checkbox" class="mock-sheet-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                        data-student-id="{{ $sheet->student_id }}" data-mock-exam-id="{{ $sheet->mock_exam_id }}"
                                        onchange="updateMockBulkActions()">
                                </td>
                            @endif
                            <td class="p-3 font-semibold">{{ $sheet->student->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $sheet->schoolClass->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $sheet->mockExam->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ number_format($sheet->average_score ?? 0, 2) }}</td>
                            @if(!auth()->user()->hasRole('student'))
                                <td class="p-3">
                                    @if((int) ($sheet->is_approved ?? 0) === 1)
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 font-bold uppercase">Approved</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-700 font-bold uppercase">Pending</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('result.mock-student-sheet', ['studentId' => $sheet->student_id, 'mockExamId' => $sheet->mock_exam_id]) }}"
                                    class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-600 hover:text-white transition-all duration-200 font-semibold text-xs border border-blue-200 hover:border-blue-600 shadow-sm group">
                                    <i class="fas fa-file-invoice group-hover:scale-110 transition-transform"></i> 
                                    <span>View Sheet</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('student') ? 5 : ($canBulkManage ? 7 : 6) }}" class="p-8 text-center text-gray-500">
                                No mock result sheets available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sheets instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($sheets, 'links') && $sheets->hasPages())
            <div class="mt-6">
                {{ $sheets->links() }}
            </div>
        @endif
    </div>

    @if($canBulkManage)
        <script>
            function toggleSelectAllMockSheets(checkbox) {
                const checkboxes = document.querySelectorAll('.mock-sheet-checkbox');
                checkboxes.forEach((cb) => {
                    cb.checked = checkbox.checked;
                });
                updateMockBulkActions();
            }

            function updateMockBulkActions() {
                const selectedCount = document.querySelectorAll('.mock-sheet-checkbox:checked').length;
                const bulkActions = document.getElementById('bulk-actions');
                if (bulkActions) {
                    bulkActions.style.display = selectedCount > 0 ? 'flex' : 'none';
                }
            }

            function getSelectedMockSheets() {
                return Array.from(document.querySelectorAll('.mock-sheet-checkbox:checked')).map((cb) => ({
                    student_id: cb.getAttribute('data-student-id'),
                    mock_exam_id: cb.getAttribute('data-mock-exam-id')
                }));
            }

            function approveSelectedMockSheets() {
                const sheets = getSelectedMockSheets();
                if (sheets.length === 0) {
                    alert('Please select at least one mock sheet to approve');
                    return;
                }

                if (!confirm(`Are you sure you want to approve ${sheets.length} mock sheet(s)?`)) {
                    return;
                }

                performBulkMockApproval(sheets, '{{ route("result.mock-bulk-approve-sheets") }}', 'approving');
            }

            function disapproveSelectedMockSheets() {
                const sheets = getSelectedMockSheets();
                if (sheets.length === 0) {
                    alert('Please select at least one mock sheet to disapprove');
                    return;
                }

                if (!confirm(`Are you sure you want to disapprove ${sheets.length} mock sheet(s)?`)) {
                    return;
                }

                performBulkMockApproval(sheets, '{{ route("result.mock-bulk-disapprove-sheets") }}', 'disapproving');
            }

            function performBulkMockApproval(sheets, url, actionLabel) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ sheets: sheets })
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('An error occurred while ' + actionLabel + ' mock sheets');
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                updateMockBulkActions();
            });
        </script>
    @endif
@endsection

