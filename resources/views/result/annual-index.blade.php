@extends('layouts.dashboard')

@section('title', 'Annual Summary Reports')

@section('dashboard')
    <div class="space-y-6">
        @if(auth()->user()->hasRole('student'))
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center space-y-4">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Your Academic Journey</h1>
                <p class="text-gray-500 max-w-md mx-auto">Explore your annual academic performance across all sessions. Select a year below to view your full summary report.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($sessions as $session)
                    <a href="{{ route('student.results.annual', [$student->id, $session->id]) }}" 
                       class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class="fas fa-file-invoice text-6xl text-indigo-600"></i>
                        </div>
                        <div class="relative z-10">
                            <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Academic Session</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $session->name }}</h3>
                            
                            <div class="flex items-center justify-between mt-6">
                                <span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-tighter">Approved</span>
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center transform group-hover:translate-x-1 transition-transform shadow-sm">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-center">
                        <div class="text-gray-400 mb-4 text-4xl">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No Annual Reports Found</h3>
                        <p class="text-gray-500">You don't have any approved annual reports yet.</p>
                    </div>
                @endforelse
            </div>
        @else
            <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Annual Academic Reports</h1>
                    <p class="text-gray-500 text-sm">Select a student to view their session-wide summary report.</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('student.results.annual.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Academic Session</label>
                        <select name="academic_year_id" class="w-full rounded-lg border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @foreach($allSessions as $session)
                                <option value="{{ $session->id }}" {{ $selectedYearId == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }} {{ $session->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Filter by Class</label>
                        <select name="class_id" class="w-full rounded-lg border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md flex items-center justify-center space-x-2">
                            <i class="fas fa-filter text-xs"></i>
                            <span>Apply Filters</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Students List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Annual Reports ({{ $students->total() }} total)</h3>
                        @if($students->count() > 0)
                        <div class="flex gap-2" id="bulk-actions" style="display: none;">
                            <button onclick="approveSelected()" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                                <i class="fas fa-check mr-1"></i>Approve Selected
                            </button>
                            <button onclick="disapproveSelected()" class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors cursor-pointer">
                                <i class="fas fa-times mr-1"></i>Disapprove Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-bold tracking-widest">
                            <tr>
                                <th class="px-6 py-4 border-b w-12">
                                    <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                </th>
                                <th class="px-6 py-4 border-b">Student Info</th>
                                <th class="px-6 py-4 border-b">Class</th>
                                <th class="px-6 py-4 border-b">Admission No</th>
                                <th class="px-6 py-4 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $student)
                                @php
                                    $enrollment = $student->enrollments->first();
                                    $class = $enrollment ? $enrollment->schoolClass : null;
                                @endphp
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="report-checkbox" data-student-id="{{ $student->id }}" data-year-id="{{ $selectedYearId }}" onchange="updateBulkActions()" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $student->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-medium uppercase">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold uppercase tracking-wide">
                                            {{ $class->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-500 uppercase tracking-tighter">
                                        {{ $student->studentProfile->admission_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($selectedYear)
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('student.results.annual', [$student->id, $selectedYearId]) }}" 
                                                   class="inline-flex items-center space-x-2 px-3 py-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                                                    <i class="fas fa-eye"></i>
                                                    <span>View</span>
                                                </a>

                                                @php
                                                    $summary = $student->annualSummaries->first();
                                                    $isApproved = $summary ? $summary->is_approved : false;
                                                    $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->is_super_admin;
                                                @endphp

                                                @if($isAdmin && !$isApproved)
                                                    <button onclick="approveAtIndex('{{ $student->id }}', '{{ $selectedYearId }}', this)" 
                                                            class="inline-flex items-center space-x-2 px-3 py-1.5 bg-green-600 text-white text-[10px] font-bold rounded-lg hover:bg-green-700 transition-all shadow-sm">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Approve</span>
                                                    </button>
                                                @elseif($isApproved)
                                                    <span class="px-2 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-tighter rounded border border-green-100">
                                                        Approved
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-amber-500 text-[10px] font-black italic uppercase tracking-widest">No Session Selected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users-slash text-gray-300 text-4xl mb-4"></i>
                                            <p class="text-gray-500 font-medium uppercase tracking-widest text-xs">No students found matching current filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.report-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateBulkActions();
    }

    function updateBulkActions() {
        const selectedCount = document.querySelectorAll('.report-checkbox:checked').length;
        const bulkActions = document.getElementById('bulk-actions');
        if (bulkActions) {
            bulkActions.style.display = selectedCount > 0 ? 'flex' : 'none';
        }
    }

    function getSelectedReports() {
        return Array.from(document.querySelectorAll('.report-checkbox:checked'))
            .map(cb => ({
                student_id: cb.getAttribute('data-student-id'),
                year_id: cb.getAttribute('data-year-id')
            }));
    }

    function approveSelected() {
        const sheets = getSelectedReports();
        if (sheets.length === 0) {
            alert('Please select at least one report to approve');
            return;
        }

        if (!confirm(`Are you sure you want to approve ${sheets.length} annual report(s)?`)) {
            return;
        }

        performBulkApproveSheets(sheets);
    }

    function disapproveSelected() {
        const sheets = getSelectedReports();
        if (sheets.length === 0) {
            alert('Please select at least one report to disapprove');
            return;
        }

        if (!confirm(`Are you sure you want to disapprove ${sheets.length} annual report(s)?`)) {
            return;
        }

        performBulkDisapproveSheets(sheets);
    }

    function performBulkApproveSheets(sheets) {
        const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

        fetch('{{ route("student.results.annual.bulk-approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sheets: sheets })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof window.showToast === 'function') window.showToast(data.message, 'success'); else alert(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                if (typeof window.showToast === 'function') window.showToast('Error: ' + data.message, 'error'); else alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof window.showToast === 'function') window.showToast('An error occurred while approving reports', 'error'); else alert('An error occurred while approving reports');
        });
    }

    function performBulkDisapproveSheets(sheets) {
        const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

        fetch('{{ route("student.results.annual.bulk-disapprove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sheets: sheets })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof window.showToast === 'function') window.showToast(data.message, 'success'); else alert(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                if (typeof window.showToast === 'function') window.showToast('Error: ' + data.message, 'error'); else alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof window.showToast === 'function') window.showToast('An error occurred while disapproving reports', 'error'); else alert('An error occurred while disapproving reports');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateBulkActions();
    });

    window.approveAtIndex = function(studentId, yearId, btn) {
        if (!confirm('Are you sure you want to approve this student\'s annual report?')) return;
        
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/student/results/annual/approve/${studentId}/${yearId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (typeof window.showToast === 'function') window.showToast(data.message || 'Approved successfully', 'success');
                // Replace button with Approved badge
                const parent = btn.parentElement;
                btn.remove();
                const badge = document.createElement('span');
                badge.className = 'px-2 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-tighter rounded border border-green-100';
                badge.innerText = 'Approved';
                parent.appendChild(badge);
            } else {
                if (typeof window.showToast === 'function') window.showToast('Error: ' + data.message, 'error'); else alert('Error: ' + data.message);
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof window.showToast === 'function') window.showToast('An error occurred.', 'error'); else alert('An error occurred.');
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    };
</script>
@endpush

