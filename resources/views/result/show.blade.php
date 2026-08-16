@extends('layouts.dashboard')

@section('title', 'Result Details')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Result Details</h1>
                    <p class="text-gray-600 mt-2">View assessment breakdown for the selected student</p>
                </div>
                <a href="{{ route('result.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                    Back to Results
                </a>
            </div>
        </div>
                

        <!-- Result Sheet (single) -->
        <div class="bg-white shadow border border-gray-300">
            <!-- Top Meta Table -->
            <div class="p-4 border-b border-gray-300">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="grid grid-cols-3 border border-gray-300">
                        <div class="p-2 border-r border-gray-300 font-semibold">NAME</div>
                        <div class="p-2 col-span-2">{{ $result->student->name ?? 'N/A' }}</div>
                        <div class="p-2 border-t border-r border-gray-300 font-semibold">ADM/REG NO</div>
                        <div class="p-2 border-t col-span-2">{{ $result->student->studentProfile->admission_number ?? 'N/A' }}</div>
                        <div class="p-2 border-t border-r border-gray-300 font-semibold">GENDER</div>
                        <div class="p-2 border-t col-span-2">{{ $result->student->studentProfile->gender ?? 'N/A' }}</div>
                    </div>
                    <div class="grid grid-cols-3 border border-gray-300">
                        <div class="p-2 border-r border-gray-300 font-semibold">CLASS</div>
                        <div class="p-2 col-span-2">{{ $result->schoolClass->name ?? 'N/A' }}</div>
                        <div class="p-2 border-t border-r border-gray-300 font-semibold">TERM</div>
                        <div class="p-2 border-t col-span-2">{{ $result->academicTerm->name ?? 'N/A' }}</div>
                        <div class="p-2 border-t border-r border-gray-300 font-semibold">AVERAGE</div>
                        <div class="p-2 border-t col-span-2">{{ number_format((float)($result->class_average ?? 0), 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Subjects Table (single row) -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs border-t border-b border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2 whitespace-nowrap">SUBJECT</th>
                            <th class="border border-gray-300 p-2 text-center">CA1 (10)</th>
                            <th class="border border-gray-300 p-2 text-center">CA2 (10)</th>
                            <th class="border border-gray-300 p-2 text-center">CA3 (10)</th>
                            <th class="border border-gray-300 p-2 text-center">EXAM (70)</th>
                            <th class="border border-gray-300 p-2 text-center">TOTAL (100)</th>
                            <th class="border border-gray-300 p-2 text-center">MAX (100)</th>
                            <th class="border border-gray-300 p-2 text-center">MIN (100)</th>
                            <th class="border border-gray-300 p-2 text-center">CLASS AVE</th>
                            <th class="border border-gray-300 p-2 text-center">POSITION</th>
                            <th class="border border-gray-300 p-2 text-center">GRADE</th>
                            <th class="border border-gray-300 p-2 text-center">REMARK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2 font-medium">{{ $result->subject->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->ca1 }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->ca2 }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->ca3 }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->exam }}</td>
                            <td class="border border-gray-300 p-2 text-center font-semibold">{{ $result->total ?? ($result->ca1 + $result->ca2 + $result->ca3 + $result->exam) }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->class_highest ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->class_lowest ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->class_average ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-center">
                                {{ $result->position ? $result->position . ((($result->position % 100) >= 11 && ($result->position % 100) <= 13) ? 'th' : (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$result->position % 10])) : '-' }}
                            </td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->grade ?? '-' }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $result->remark ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Attached Comments Section (table style) -->
            <div class="border-t border-gray-300">
                <div class="grid grid-cols-3 text-xs">
                    <div class="col-span-1 border-r border-gray-300">
                        <div class="p-2 border-b border-gray-300 font-semibold">School Head Name</div>
                        <div class="p-2">{{ $result->approver->name ?? '—' }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="grid grid-cols-2">
                            <div class="border-r border-gray-300">
                                <div class="p-2 border-b border-gray-300 font-semibold">School Head Comment</div>
                                <div class="p-2">{{ $result->school_head_comment ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="p-2 border-b border-gray-300 font-semibold">Class Teacher Comment</div>
                                <div class="p-2">
                                    @if(auth()->user()->hasRole('form_teacher'))
                                        <form id="commentForm" class="space-y-2">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <textarea id="form_teacher_comment" name="form_teacher_comment" rows="2"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                      placeholder="Enter class teacher comment..." required>{{ old('form_teacher_comment', $result->form_teacher_comment) }}</textarea>
                                            <div class="flex items-center space-x-3">
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 cursor-pointer">Save</button>
                                                <span id="commentStatus" class="text-sm text-gray-600"></span>
                                            </div>
                                        </form>
                                        <script>
                                        document.getElementById('commentForm').addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            const statusEl = document.getElementById('commentStatus');
                                            statusEl.textContent = 'Saving...';
                                            const formData = new FormData(this);
                                            fetch(`{{ route('result.add-comment', $result) }}` , {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': formData.get('_token') },
                                                body: formData
                                            })
                                            .then(r => r.json())
                                            .then(data => {
                                                if (data.success) {
                                                    statusEl.textContent = 'Saved';
                                                    window.setTimeout(() => window.location.reload(), 500);
                                                } else {
                                                    statusEl.textContent = 'Error: ' + (data.message || 'Failed to save');
                                                }
                                            })
                                            .catch(() => { statusEl.textContent = 'Network error while saving'; });
                                        });
                                        </script>
                                    @else
                                        <div class="text-gray-900">{{ $result->form_teacher_comment ?? '—' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex items-center space-x-3">
            @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                <a href="{{ route('result.edit', $result) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">Edit</a>
            @endif

            @if(!$result->is_approved)
                <form method="POST" action="{{ route('result.approve-sheet', ['studentId' => $result->student_id, 'termId' => $result->term_id]) }}" onsubmit="return confirm('Approve this result sheet?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">Approve</button>
                </form>
            @else
                <form method="POST" action="{{ route('result.disapprove-sheet', ['studentId' => $result->student_id, 'termId' => $result->term_id]) }}" onsubmit="return confirm('Disapprove this result sheet?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors cursor-pointer">Disapprove</button>
                </form>
            @endif
        </div>


    </div>
</div>
@endsection



