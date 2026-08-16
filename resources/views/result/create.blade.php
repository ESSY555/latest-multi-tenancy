@extends('layouts.dashboard')

@section('title', 'Add New Result')

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Add New Result</h1>
                        <p class="text-gray-600 mt-2">Enter student assessment scores</p>
                    </div>
                    <a href="{{ route('result.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                        Back to Results
                    </a>
                </div>
            </div>

            @if($errors->has('error'))
            <div class="mb-6 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700">
                {{ $errors->first('error') }}
            </div>
            @endif

            <!-- Current Academic Year Info -->
            @if($currentAcademicYear)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Current Academic Year</h3>
                        <div class="mt-1 text-sm text-green-700">
                            <p><strong>{{ $currentAcademicYear->name }}</strong> ({{ $currentAcademicYear->start_date->format('M Y') }} - {{ $currentAcademicYear->end_date->format('M Y') }})</p>
                            <p class="text-xs mt-1">Available terms: {{ $terms->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">No Active Academic Year</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p>Please set an active academic year first. <a href="{{ route('academic-calendar.index') }}" class="underline">Go to Academic Calendar</a></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('result.bulk-store') }}" method="POST" class="p-6" id="result-create-form">
                    @csrf

                    <div id="form-ajax-alert" class="hidden mb-6 px-4 py-3 rounded-md"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Result Type Selection -->
                        <div>
                            <label for="result_type" class="block text-sm font-medium text-gray-700 mb-2">Result Type *</label>
                            <select name="result_type"
                                    id="result_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required
                                    onchange="toggleResultType()">
                                <option value="termly" {{ old('result_type', 'termly') === 'termly' ? 'selected' : '' }}>Termly</option>
                                <option value="mock" {{ old('result_type') === 'mock' ? 'selected' : '' }}>Mock</option>
                            </select>
                            @error('result_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mock Exam Selection -->
                        <div id="mock_exam_wrap" class="hidden">
                            <label for="mock_exam_id" class="block text-sm font-medium text-gray-700 mb-2">Mock Exam *</label>
                            <select name="mock_exam_id"
                                    id="mock_exam_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Mock Exam</option>
                                @foreach(($mockExams ?? collect()) as $mockExam)
                                    <option value="{{ $mockExam->id }}" {{ old('mock_exam_id') == $mockExam->id ? 'selected' : '' }}>
                                        {{ $mockExam->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="mock_exam_help" class="mt-1 text-xs text-amber-600 hidden">
                                No active mock exam found. Please create/activate a mock exam first.
                            </p>
                            @error('mock_exam_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class Selection -->
                        <div class="md:col-span-2">
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                            <select name="class_id" 
                                    id="class_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required
                                    onchange="loadClassData()">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <select name="subject_id" 
                                    id="subject_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Term Selection -->
                        <div id="term_wrap">
                            <label for="term_id" class="block text-sm font-medium text-gray-700 mb-2">Academic Term *</label>
                            <select name="term_id" 
                                    id="term_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Select Term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" 
                                            {{ (old('term_id') == $term->id) ? 'selected' :
        (($currentAcademicYear && $loop->first && !old('term_id')) ? 'selected' : '') }}>
                                        {{ $term->name }} - {{ $term->academicYear->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('term_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Students Table Section -->
                    <div id="students-table-container" class="mt-8 hidden">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 flex-grow">Bulk Result Entry</h3>
                        </div>
                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adm No</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca1">CA1 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca2">CA2 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca3">CA3 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-exam">Exam (0-70)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="students-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                            <!-- Pagination Controls -->
                            <div id="pagination-container" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"></div>
                        </div>
                        <p id="no-students-msg" class="text-gray-500 mt-4 hidden">No students found for this selection.</p>
                    </div>
                    
                    {{-- Attendance Section (Commented out for now)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Attendance Record</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Present -->
                            <div>
                                <label for="attendance_present" class="block text-sm font-medium text-gray-700 mb-2">Days Present</label>
                                <input type="number" name="attendance_present" id="attendance_present"
                                    value="{{ old('attendance_present', 0) }}" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('attendance_present')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Absent -->
                            <div>
                                <label for="attendance_absent" class="block text-sm font-medium text-gray-700 mb-2">Days Absent</label>
                                <input type="number" name="attendance_absent" id="attendance_absent"
                                    value="{{ old('attendance_absent', 0) }}" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('attendance_absent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Late -->
                            <div>
                                <label for="attendance_late" class="block text-sm font-medium text-gray-700 mb-2">Days Late</label>
                                <input type="number" name="attendance_late" id="attendance_late" value="{{ old('attendance_late', 0) }}"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('attendance_late')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    --}}

                    <!-- Grading System Info -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Grading System</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>A (70-100):</strong> Excellent</p>
                                    <p><strong>B (60-69):</strong> Good</p>
                                    <p><strong>C (50-59):</strong> Credit</p>
                                    <p><strong>D (40-49):</strong> Pass</p>
                                    <p><strong>F (0-39):</strong> Fail</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Signature Section (Commented out) -->
                    {{--
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 uppercase mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Teacher's Signature *
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-2 flex flex-col items-center">
                                <canvas id="teacher-signature-pad" class="w-full h-40 cursor-crosshair bg-white touch-none" style="max-width: 500px;"></canvas>
                                <div class="w-full border-t border-gray-200 mt-2 pt-2 flex justify-between items-center px-4">
                                    <span class="text-xs text-gray-400 font-medium italic">Draw your signature above</span>
                                    <button type="button" onclick="clearSignature()" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase transition-colors px-2 py-1 rounded hover:bg-red-50">
                                        Clear Signature
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="form_teacher_signature" id="form_teacher_signature_input">
                            @error('form_teacher_signature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    --}}

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium cursor-pointer">
                            Save Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
    let signaturePad;
    let unsavedResults = {}; // Client-side state for pagination

    function saveCurrentPageToState() {
        const tbody = document.getElementById('students-table-body');
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const studentIdInput = row.querySelector('input[name*="[student_id]"]');
            if (!studentIdInput) return;
            
            const studentId = studentIdInput.value;
            if (!unsavedResults[studentId]) {
                unsavedResults[studentId] = { student_id: studentId };
            }
            
            const inputs = row.querySelectorAll('input[type="number"], input[type="hidden"]');
            inputs.forEach(input => {
                const match = input.name.match(/results\[\d+\]\[(ca1|ca2|ca3|exam)\]/);
                // Only remember fields the user actually typed a value into. Storing
                // empty strings here would later override real saved DB values with
                // blanks when the row is re-rendered (e.g. after pagination), hiding
                // results that are actually saved.
                if (match && !input.disabled && input.value !== '') {
                    unsavedResults[studentId][match[1]] = input.value;
                }
            });
        });
    }

    function calcRowTotal(studentId) {
        const ca1 = parseFloat(document.getElementById(`ca1_${studentId}`)?.value) || 0;
        const ca2 = parseFloat(document.getElementById(`ca2_${studentId}`)?.value) || 0;
        const ca3 = parseFloat(document.getElementById(`ca3_${studentId}`)?.value) || 0;
        const exam = parseFloat(document.getElementById(`exam_${studentId}`)?.value) || 0;
        
        const total = ca1 + ca2 + ca3 + exam;
        const totalEl = document.getElementById(`total_${studentId}`);
        if(totalEl) totalEl.textContent = total;
    }

    function validateInput(input, min, max) {
        if (input.value !== '') {
            let val = parseFloat(input.value);
            if (val > max) input.value = max;
            if (val < min) input.value = min;
        }
    }

    let currentPage = 1;
    // Monotonic token used to discard out-of-order AJAX responses. Selecting
    // class -> subject -> term fires several overlapping requests; without this
    // a slow earlier response can arrive last and overwrite the correct data
    // (leaving already-saved results blank).
    let latestRequestId = 0;

    function checkAndLoadData(page = 1, isPagination = false) {
        // Only preserve in-progress typing when paginating within the SAME
        // selection. On a selection change we must not re-capture the previous
        // selection's rows, or their values bleed into the new render.
        if (isPagination && currentPage && document.getElementById('students-table-body')?.innerHTML.trim() !== '') {
            saveCurrentPageToState();
        }

        // Invalidate any in-flight request so its (stale) response is ignored.
        const requestId = ++latestRequestId;

        currentPage = page;
        const classId = document.getElementById('class_id').value;
        const subjectId = document.getElementById('subject_id').value;
        const resultType = document.getElementById('result_type').value;
        const termId = document.getElementById('term_id').value;
        const mockExamId = document.getElementById('mock_exam_id').value;

        const tableContainer = document.getElementById('students-table-container');
        const tbody = document.getElementById('students-table-body');
        const noStudentsMsg = document.getElementById('no-students-msg');
        const paginationContainer = document.getElementById('pagination-container');
        
        // Reset
        tableContainer.classList.add('hidden');
        noStudentsMsg.classList.add('hidden');
        tbody.innerHTML = '';
        paginationContainer.innerHTML = '';

        if (!classId || !subjectId || (resultType === 'termly' && !termId) || (resultType === 'mock' && !mockExamId)) {
            return;
        }

        // Show loading state
        tableContainer.classList.remove('hidden');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">⏳ Loading students and existing results...</td></tr>';

        const url = new URL('/result/api/bulk-entry-data', window.location.origin);
        url.searchParams.append('class_id', classId);
        url.searchParams.append('subject_id', subjectId);
        url.searchParams.append('result_type', resultType);
        url.searchParams.append('page', currentPage);
        if (resultType === 'termly') url.searchParams.append('term_id', termId);
        if (resultType === 'mock') url.searchParams.append('mock_exam_id', mockExamId);
        // Cache-bust: without this, the browser can serve a stale cached response
        // for an identical URL+querystring (e.g. re-selecting the same class/
        // subject/term after saving), showing pre-save data even though the
        // server already has the fresh result.
        url.searchParams.append('_ts', Date.now());

        fetch(url, { cache: 'no-store' })
            .then(res => res.json())
            .then(res => {
                // Drop this response if a newer request has since been issued.
                if (requestId !== latestRequestId) return;

                tbody.innerHTML = '';
                const paginator = res.data;
                const students = paginator.data || [];

                if (res.success && students.length > 0) {
                    students.forEach(student => {
                        let ca1 = student.ca1 !== null ? student.ca1 : '';
                        let ca2 = student.ca2 !== null ? student.ca2 : '';
                        let ca3 = student.ca3 !== null ? student.ca3 : '';
                        let exam = student.exam !== null ? student.exam : '';
                        
                        // Override with locally unsaved values only when the user
                        // actually typed something. An empty/blank stored value must
                        // never replace a real saved value coming back from the server.
                        const uv = unsavedResults[student.student_id];
                        if (uv) {
                            if (uv.ca1 !== undefined && uv.ca1 !== '') ca1 = uv.ca1;
                            if (uv.ca2 !== undefined && uv.ca2 !== '') ca2 = uv.ca2;
                            if (uv.ca3 !== undefined && uv.ca3 !== '') ca3 = uv.ca3;
                            if (uv.exam !== undefined && uv.exam !== '') exam = uv.exam;
                        }
                        
                        const isExisting = student.has_result;
                        const isMock = resultType === 'mock';
                        const maxCa1 = isMock ? 10 : 10;
                        const maxCa2 = isMock ? 20 : 10;
                        const maxExam = isMock ? 90 : 70;

                        // A field is locked only when it already holds a saved value
                        // (non-null). Empty fields stay editable so the teacher can
                        // return later to fill them in. Admins can edit everything.
                        const isFieldFilled = (dbVal) => dbVal !== null && dbVal !== '';
                        const attrsFor = (dbVal) => (isExisting && !res.isAdmin && isFieldFilled(dbVal))
                            ? 'readonly disabled class="bg-gray-100 px-2 py-1 w-16 border border-gray-300 rounded"'
                            : 'class="px-2 py-1 w-16 border border-gray-300 rounded focus:ring-green-500"';

                        const ca1Attr = attrsFor(student.ca1);
                        const ca2Attr = attrsFor(student.ca2);
                        const ca3Attr = attrsFor(student.ca3);
                        const examAttr = attrsFor(student.exam);

                        // Row status badge: fully locked vs. partially filled.
                        const applicableVals = isMock
                            ? [student.ca1, student.ca2, student.exam]
                            : [student.ca1, student.ca2, student.ca3, student.exam];
                        const filledCount = applicableVals.filter(isFieldFilled).length;
                        let statusBadge = '';
                        if (isExisting && !res.isAdmin) {
                            statusBadge = (filledCount === applicableVals.length)
                                ? '<span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Locked</span>'
                                : '<span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partial</span>';
                        }

                        const ca3Input = isMock ?
                            `<input type="hidden" name="results[${student.student_id}][ca3]" value="0"> <span class="text-gray-400">-</span>` :
                            `<input type="number" step="0.1" min="0" max="10" id="ca3_${student.student_id}" name="results[${student.student_id}][ca3]" value="${ca3}" ${ca3Attr} oninput="validateInput(this, 0, 10); calcRowTotal(${student.student_id})">`;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="px-3 py-3 text-sm font-medium text-gray-900">
                                <input type="hidden" name="results[${student.student_id}][student_id]" value="${student.student_id}">
                                ${student.name}
                                ${statusBadge}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500">${student.admission_number}</td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" min="0" max="${maxCa1}" id="ca1_${student.student_id}" name="results[${student.student_id}][ca1]" value="${ca1}" ${ca1Attr} oninput="validateInput(this, 0, ${maxCa1}); calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" min="0" max="${maxCa2}" id="ca2_${student.student_id}" name="results[${student.student_id}][ca2]" value="${ca2}" ${ca2Attr} oninput="validateInput(this, 0, ${maxCa2}); calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm text-gray-500">${ca3Input}</td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" min="0" max="${maxExam}" id="exam_${student.student_id}" name="results[${student.student_id}][exam]" value="${exam}" ${examAttr} oninput="validateInput(this, 0, ${maxExam}); calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm font-bold text-green-600" id="total_${student.student_id}">0</td>
                        `;
                        tbody.appendChild(tr);
                        calcRowTotal(student.student_id);
                    });

                    renderPagination(paginator);
                } else {
                    noStudentsMsg.classList.remove('hidden');
                    tableContainer.classList.add('hidden');
                }
            })
            .catch(err => {
                if (requestId !== latestRequestId) return;
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-red-500 py-4">❌ Failed to load students</td></tr>';
            });
    }

    function renderPagination(paginator) {
        const container = document.getElementById('pagination-container');
        if (!paginator || paginator.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = `
            <div class="flex items-center justify-between w-full">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">${paginator.from || 0}</span> to <span class="font-medium">${paginator.to || 0}</span> of <span class="font-medium">${paginator.total || 0}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        `;

        // Previous Button
        if (paginator.current_page > 1) {
            html += `<button type="button" onclick="checkAndLoadData(${paginator.current_page - 1}, true)" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Previous</button>`;
        } else {
            html += `<span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">Previous</span>`;
        }

        // Page Numbers
        for (let i = 1; i <= paginator.last_page; i++) {
            if (i === paginator.current_page) {
                html += `<span class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600 z-10">${i}</span>`;
            } else {
                html += `<button type="button" onclick="checkAndLoadData(${i}, true)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">${i}</button>`;
            }
        }

        // Next Button
        if (paginator.current_page < paginator.last_page) {
            html += `<button type="button" onclick="checkAndLoadData(${paginator.current_page + 1}, true)" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Next</button>`;
        } else {
            html += `<span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">Next</span>`;
        }

        html += `
                        </nav>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML = html;
    }

    // Replace loadClassData
    function loadClassData() {
        unsavedResults = {}; // Reset state when class changes
        const classId = document.getElementById('class_id').value;
        const subjectSelect = document.getElementById('subject_id');

        if (!classId) {
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            subjectSelect.disabled = true;
            checkAndLoadData();
            return;
        }

        subjectSelect.innerHTML = '<option value="">⏳ Loading subjects...</option>';
        subjectSelect.disabled = true;

        fetch(`/api/classes/${classId}/subjects`)
            .then(r => r.json())
            .then(res => {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                if (res && res.success && Array.isArray(res.subjects)) {
                    if (res.subjects.length > 0) {
                        res.subjects.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.name;
                            subjectSelect.appendChild(opt);
                        });
                        subjectSelect.disabled = false;
                    } else {
                        subjectSelect.innerHTML = '<option value="">No subjects assigned to this class</option>';
                    }
                } else {
                    subjectSelect.innerHTML = '<option value="">❌ Error loading subjects</option>';
                }
                checkAndLoadData();
            })
            .catch(err => {
                console.error(err);
                subjectSelect.innerHTML = '<option value="">❌ Failed to load subjects</option>';
                checkAndLoadData();
            });
    }

    // Attach listeners to other selects
    document.addEventListener('DOMContentLoaded', function() {
        toggleResultType();

        document.getElementById('subject_id').addEventListener('change', () => { unsavedResults = {}; checkAndLoadData(1); });
        document.getElementById('term_id').addEventListener('change', () => { unsavedResults = {}; checkAndLoadData(1); });
        document.getElementById('mock_exam_id').addEventListener('change', () => { unsavedResults = {}; checkAndLoadData(1); });

        // Initialize Signature Pad
        const canvas = document.getElementById('teacher-signature-pad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        window.clearSignature = () => signaturePad.clear();

        // Handle form submission via AJAX so the page never navigates away.
        const resultForm = document.getElementById('result-create-form');
        resultForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitResultForm();
        });
    });

    function showFormAlert(type, message) {
        const el = document.getElementById('form-ajax-alert');
        if (!el) return;
        el.textContent = message;
        el.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-700', 'bg-red-50', 'border-red-200', 'text-red-700', 'bg-yellow-50', 'border-yellow-200', 'text-yellow-700', 'border');
        const styles = {
            success: ['bg-green-50', 'border', 'border-green-200', 'text-green-700'],
            warning: ['bg-yellow-50', 'border', 'border-yellow-200', 'text-yellow-700'],
            error: ['bg-red-50', 'border', 'border-red-200', 'text-red-700'],
        };
        el.classList.add(...(styles[type] || styles.error));
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function submitResultForm() {
        const resultForm = document.getElementById('result-create-form');
        const submitBtn = resultForm.querySelector('button[type="submit"]');

        // Save the currently visible inputs to state
        saveCurrentPageToState();

        if (Object.keys(unsavedResults).length === 0) {
            showFormAlert('error', 'Please enter at least one score before saving.');
            return;
        }

        const formData = new FormData();
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', token);
        formData.append('class_id', document.getElementById('class_id').value);
        formData.append('subject_id', document.getElementById('subject_id').value);
        formData.append('result_type', document.getElementById('result_type').value);
        formData.append('term_id', document.getElementById('term_id').value || '');
        formData.append('mock_exam_id', document.getElementById('mock_exam_id').value || '');

        for (const studentId in unsavedResults) {
            const data = unsavedResults[studentId];
            formData.append(`results[${studentId}][student_id]`, studentId);
            ['ca1', 'ca2', 'ca3', 'exam'].forEach(field => {
                if (data[field] !== undefined && data[field] !== '') {
                    formData.append(`results[${studentId}][${field}]`, data[field]);
                }
            });
        }

        submitBtn.disabled = true;
        const originalBtnText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';

        fetch(resultForm.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: formData,
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                showFormAlert(data.locked > 0 && data.updated + data.created > 0 ? 'warning' : 'success', data.message || 'Results saved successfully.');
                unsavedResults = {};
                // Refresh the table in place so saved/locked state reflects immediately.
                checkAndLoadData(currentPage);
            } else {
                const errMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to save results.');
                showFormAlert('error', errMsg);
            }
        })
        .catch(() => {
            showFormAlert('error', 'A network error occurred while saving. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        });
    }

    function toggleResultType() {
        unsavedResults = {}; // Reset state on result type change
        const resultType = document.getElementById('result_type')?.value || 'termly';
        const termWrap = document.getElementById('term_wrap');
        const termSelect = document.getElementById('term_id');
        const mockWrap = document.getElementById('mock_exam_wrap');
        const mockSelect = document.getElementById('mock_exam_id');
        const mockHelp = document.getElementById('mock_exam_help');

        const thCa1 = document.getElementById('th-ca1');
        const thCa2 = document.getElementById('th-ca2');
        const thCa3 = document.getElementById('th-ca3');
        const thExam = document.getElementById('th-exam');

        const isMock = resultType === 'mock';

        if (termWrap) termWrap.classList.toggle('hidden', isMock);
        if (mockWrap) mockWrap.classList.toggle('hidden', !isMock);

        if (isMock) {
            if (thCa1) thCa1.textContent = 'TEST (0-10)';
            if (thCa2) thCa2.textContent = 'PRACTICAL (0-20)';
            if (thCa3) thCa3.textContent = 'CA3';
            if (thExam) thExam.textContent = 'EXAM (0-90)';
        } else {
            if (thCa1) thCa1.textContent = 'CA1 (0-10)';
            if (thCa2) thCa2.textContent = 'CA2 (0-10)';
            if (thCa3) thCa3.textContent = 'CA3 (0-10)';
            if (thExam) thExam.textContent = 'Exam (0-70)';
        }

        if (termSelect) {
            termSelect.required = !isMock;
            termSelect.disabled = isMock;
        }
        if (mockSelect) {
            const hasRealMockOptions = mockSelect.options.length > 1;
            if (isMock && hasRealMockOptions && !mockSelect.value) {
                mockSelect.selectedIndex = 1;
            }
            mockSelect.required = isMock && hasRealMockOptions;
            mockSelect.disabled = !isMock;
            if (mockHelp) {
                mockHelp.classList.toggle('hidden', !isMock || hasRealMockOptions);
            }
        }

        checkAndLoadData();
    }
    </script>
@endsection

