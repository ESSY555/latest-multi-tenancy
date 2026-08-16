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
                <form action="{{ route('result.store') }}" method="POST" class="p-6" id="result-create-form">
                    @csrf

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

                        <!-- Student Selection -->
                        <div class="md:col-span-2">
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                            <select name="student_id" 
                                    id="student_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
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

                    <!-- Assessment Scores Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Assessment Scores</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Score 1 -->
                            <div>
                                <label id="ca1-label" for="ca1" class="block text-sm font-medium text-gray-700 mb-2">CA1 (0-10)</label>
                                <input type="number" 
                                       name="ca1" 
                                       id="ca1" 
                                       value="{{ old('ca1') }}"
                                       min="0" 
                                       max="10" 
                                       step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       onchange="calculateTotal()">
                                @error('ca1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Score 2 -->
                            <div>
                                <label id="ca2-label" for="ca2" class="block text-sm font-medium text-gray-700 mb-2">CA2 (0-10)</label>
                                <input type="number" 
                                       name="ca2" 
                                       id="ca2" 
                                       value="{{ old('ca2') }}"
                                       min="0" 
                                       max="10" 
                                       step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       onchange="calculateTotal()">
                                @error('ca2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Score 3 (termly only) -->
                            <div id="ca3-wrap">
                                <label id="ca3-label" for="ca3" class="block text-sm font-medium text-gray-700 mb-2">CA3 (0-10)</label>
                                <input type="number" 
                                       name="ca3" 
                                       id="ca3" 
                                       value="{{ old('ca3') }}"
                                       min="0" 
                                       max="10" 
                                       step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       onchange="calculateTotal()">
                                @error('ca3')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Exam -->
                            <div>
                                    <label id="exam-label" for="exam" class="block text-sm font-medium text-gray-700 mb-2">Exam (0-90)</label>
                                    <input type="number" 
                                        name="exam" 
                                        id="exam" 
                                        value="{{ old('exam') }}"
                                        min="0" 
                                        max="90" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        onchange="calculateTotal()">
                                @error('exam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Display -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Total Score:</span>
                                <span id="total-display" class="text-lg font-bold text-green-600">0</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-sm text-gray-600">Grade: </span>
                                <span id="grade-display" class="text-sm font-medium text-gray-900">-</span>
                                <span class="text-sm text-gray-600 ml-4">Remark: </span>
                                <span id="remark-display" class="text-sm font-medium text-gray-900">-</span>
                            </div>
                        </div>
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

                    <!-- Teacher Signature Section -->
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

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium cursor-pointer">
                            Create Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
    let signaturePad;

    function calculateTotal() {
        const ca1 = parseFloat(document.getElementById('ca1').value) || 0;
        const ca2 = parseFloat(document.getElementById('ca2').value) || 0;
        const ca3 = parseFloat(document.getElementById('ca3').value) || 0;
        const exam = parseFloat(document.getElementById('exam').value) || 0;

        const total = ca1 + ca2 + ca3 + exam;

        document.getElementById('total-display').textContent = total;

        // Determine grade and remark
        let grade, remark;
        if (total >= 70 && total <= 100) {
            grade = 'A';
            remark = 'Excellent';
        } else if (total >= 60 && total <= 69) {
            grade = 'B';
            remark = 'Good';
        } else if (total >= 50 && total <= 59) {
            grade = 'C';
            remark = 'credit';
        } else if (total >= 40 && total <= 49) {
            grade = 'D';
            remark = 'pass';
        } else {
            grade = 'F';
            remark = 'Fail';
        }

        document.getElementById('grade-display').textContent = grade;
        document.getElementById('remark-display').textContent = remark;
    }

    function loadClassData() {
        const classId = document.getElementById('class_id').value;
        const studentSelect = document.getElementById('student_id');
        const subjectSelect = document.getElementById('subject_id');

        if (!classId) {
            // Reset and disable when no class selected
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            studentSelect.disabled = true;
            subjectSelect.disabled = true;
            return;
        }

        // Show loading states
        studentSelect.innerHTML = '<option value="">⏳ Loading students...</option>';
        subjectSelect.innerHTML = '<option value="">⏳ Loading subjects...</option>';
        studentSelect.disabled = true;
        subjectSelect.disabled = true;

        // Fetch students and subjects in parallel
        console.log(`Fetching data for class: ${classId}`);

        const studentsPromise = fetch(`/api/classes/${classId}/students`)
            .then(r => {
                if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
                return r.json();
            })
            .catch(e => {
                console.error('Students fetch error:', e);
                return { success: false, message: e.message };
            });

        const subjectsPromise = fetch(`/api/classes/${classId}/subjects`)
            .then(r => {
                if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
                return r.json();
            })
            .catch(e => {
                console.error('Subjects fetch error:', e);
                return { success: false, message: e.message };
            });

        Promise.all([studentsPromise, subjectsPromise]).then(([studentsResp, subjectsResp]) => {
            // Populate students
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            if (studentsResp && studentsResp.success && Array.isArray(studentsResp.students)) {
                if (studentsResp.students.length > 0) {
                    studentsResp.students.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = `${s.name} (${s.email ?? ''})`;
                        studentSelect.appendChild(opt);
                    });
                    studentSelect.disabled = false;
                } else {
                    studentSelect.innerHTML = '<option value="">No active students found in this class</option>';
                }
            } else {
                studentSelect.innerHTML = '<option value="">❌ Error loading students</option>';
                console.error('Students response error:', studentsResp?.message);
            }

            // Populate subjects
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            if (subjectsResp && subjectsResp.success && Array.isArray(subjectsResp.subjects)) {
                if (subjectsResp.subjects.length > 0) {
                    subjectsResp.subjects.forEach(sub => {
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
                console.error('Subjects response error:', subjectsResp?.message);
            }
        }).catch(err => {
            console.error('Global class data load error:', err);
            studentSelect.innerHTML = '<option value="">❌ Failed to load students</option>';
            subjectSelect.innerHTML = '<option value="">❌ Failed to load subjects</option>';
            alert('An error occurred while loading class data. Please try again.');
        });
    }

    // Calculate total on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleResultType();
        // Disable student and subject selects until class is picked
        const studentSelect = document.getElementById('student_id');
        const subjectSelect = document.getElementById('subject_id');
        if (document.getElementById('class_id').value === '') {
            studentSelect.disabled = true;
            subjectSelect.disabled = true;
        }
        calculateTotal();

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

        // Handle form submission
        const resultForm = document.getElementById('result-create-form');
        resultForm.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide your signature before submitting.');
                return;
            }
            document.getElementById('form_teacher_signature_input').value = signaturePad.toDataURL();
        });
    });

    function toggleResultType() {
        const resultType = document.getElementById('result_type')?.value || 'termly';
        const termWrap = document.getElementById('term_wrap');
        const termSelect = document.getElementById('term_id');
        const mockWrap = document.getElementById('mock_exam_wrap');
        const mockSelect = document.getElementById('mock_exam_id');
        const mockHelp = document.getElementById('mock_exam_help');
        const ca1Label = document.getElementById('ca1-label');
        const ca2Label = document.getElementById('ca2-label');
        const ca3Label = document.getElementById('ca3-label');
        const examLabel = document.getElementById('exam-label');
        const ca1Input = document.getElementById('ca1');
        const ca2Input = document.getElementById('ca2');
        const ca3Input = document.getElementById('ca3');
        const ca3Wrap = document.getElementById('ca3-wrap');

        const isMock = resultType === 'mock';

        if (termWrap) termWrap.classList.toggle('hidden', isMock);
        if (mockWrap) mockWrap.classList.toggle('hidden', !isMock);

        if (isMock) {
            if (ca1Label) ca1Label.textContent = 'TEST (0-10)';
            if (ca2Label) ca2Label.textContent = 'PRACTICAL (0-20)';
            if (ca3Label) ca3Label.textContent = 'CA3 (hidden for mock)';
            if (examLabel) examLabel.textContent = 'EXAM (0-90)';
            if (document.getElementById('exam')) document.getElementById('exam').max = '90';
            if (ca1Input) ca1Input.max = '10';
            if (ca2Input) ca2Input.max = '20';
            if (ca3Input) {
                ca3Input.value = '0';
                ca3Input.disabled = true;
            }
            if (ca3Wrap) ca3Wrap.classList.add('hidden');
        } else {
            if (ca1Label) ca1Label.textContent = 'CA1 (0-10)';
            if (ca2Label) ca2Label.textContent = 'CA2 (0-10)';
            if (ca3Label) ca3Label.textContent = 'CA3 (0-10)';
            if (examLabel) examLabel.textContent = 'Exam (0-70)';
            if (document.getElementById('exam')) document.getElementById('exam').max = '70';
            if (ca1Input) ca1Input.max = '10';
            if (ca2Input) ca2Input.max = '10';
            if (ca3Input) ca3Input.disabled = false;
            if (ca3Wrap) ca3Wrap.classList.remove('hidden');
        }

        if (termSelect) {
            termSelect.required = !isMock;
            termSelect.disabled = isMock;
        }
        if (mockSelect) {
            const hasRealMockOptions = mockSelect.options.length > 1;
            if (isMock && hasRealMockOptions && !mockSelect.value) {
                mockSelect.selectedIndex = 1; // Auto-pick first available mock exam
            }
            mockSelect.required = isMock && hasRealMockOptions;
            mockSelect.disabled = !isMock;
            if (mockHelp) {
                mockHelp.classList.toggle('hidden', !isMock || hasRealMockOptions);
            }
        }

        calculateTotal();
    }
    </script>
@endsection

