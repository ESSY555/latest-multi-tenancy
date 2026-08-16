@extends('layouts.dashboard')

@section('title', 'Add Academic Exam')

@section('dashboard')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Academic Exam</h1>
            <p class="text-gray-600 mt-2">Schedule a new examination or assessment for {{ $academicYear->name }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('academic-calendar.exams.store', $academicYear->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Exam Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., First Term Final Examination">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Scope -->
                    <div>
                        <label for="exam_scope" class="block text-sm font-medium text-gray-700">Timetable Scope</label>
                        <select name="exam_scope" id="exam_scope" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="term" {{ old('exam_scope', 'term') == 'term' ? 'selected' : '' }}>Term</option>
                            <option value="mock" {{ old('exam_scope') == 'mock' ? 'selected' : '' }}>Mock</option>
                        </select>
                        @error('exam_scope')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Term Selection -->
                    <div id="term-selection-group">
                        <label for="academic_term_id" class="block text-sm font-medium text-gray-700">Term</label>
                        <select name="academic_term_id" id="academic_term_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ old('academic_term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_term_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mock Selection -->
                    <div id="mock-selection-group" style="display: none;">
                        <label for="mock_exam_id" class="block text-sm font-medium text-gray-700">Mock Exam</label>
                        <select name="mock_exam_id" id="mock_exam_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Mock Exam</option>
                            @foreach($mockExams as $mockExam)
                                <option value="{{ $mockExam->id }}" {{ old('mock_exam_id') == $mockExam->id ? 'selected' : '' }}>
                                    {{ $mockExam->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mock_exam_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Type -->
                    <div>
                        <label for="exam_type" class="block text-sm font-medium text-gray-700">Assessment Type</label>
                        <select name="exam_type" id="exam_type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Type</option>
                            <option value="midterm" {{ old('exam_type') == 'midterm' ? 'selected' : '' }}>Midterm Exam</option>
                            <option value="final" {{ old('exam_type') == 'final' ? 'selected' : '' }}>Final Examination</option>
                            <option value="quiz" {{ old('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="assignment" {{ old('exam_type') == 'assignment' ? 'selected' : '' }}>CA Assignment</option>
                            <option value="project" {{ old('exam_type') == 'project' ? 'selected' : '' }}>Project Work</option>
                            <option value="practical" {{ old('exam_type') == 'practical' ? 'selected' : '' }}>Practical Exam</option>
                            <option value="written" {{ old('exam_type', 'written') == 'written' ? 'selected' : '' }}>Written Test</option>
                            <option value="other" {{ old('exam_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('exam_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Date -->
                    <div>
                        <label for="exam_date" class="block text-sm font-medium text-gray-700">Examination Date</label>
                        <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}" required
                               min="{{ $academicYear->start_date->format('Y-m-d') }}"
                               max="{{ $academicYear->end_date->format('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('exam_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marks Configuration -->
                    <div>
                        <label for="total_marks" class="block text-sm font-medium text-gray-700">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks', 100) }}" required min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('total_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passing_marks" class="block text-sm font-medium text-gray-700">Passing Marks</label>
                        <input type="number" name="passing_marks" id="passing_marks" value="{{ old('passing_marks', 40) }}" required min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('passing_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class & Subject -->
                    <div>
                        <label for="school_class_id" class="block text-sm font-medium text-gray-700">School Class (Optional)</label>
                        <select name="school_class_id" id="school_class_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Class (Global if empty)</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject (Optional)</label>
                        <select name="subject_id" id="subject_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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

                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700">Teacher</label>
                        <select name="teacher_id" id="teacher_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g., Exam Hall A">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color & Instructions -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Exam Color Code</label>
                        <input type="color" name="color" id="color" value="{{ old('color', '#dc2626') }}"
                               class="mt-1 block w-16 h-10 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="col-span-2">
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea name="instructions" id="instructions" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Special instructions for students">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2 flex space-x-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">Publish Immediately</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_online" id="is_online" value="1" {{ old('is_online') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_online" class="ml-2 block text-sm text-gray-900">Online Examination</label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="col-span-2 flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('academic-calendar.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Exam Schedule
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scopeSelect = document.getElementById('exam_scope');
        const termGroup = document.getElementById('term-selection-group');
        const mockGroup = document.getElementById('mock-selection-group');
        const termSelect = document.getElementById('academic_term_id');
        const mockSelect = document.getElementById('mock_exam_id');

        function toggleScopeInputs() {
            const isMock = scopeSelect.value === 'mock';
            termGroup.style.display = isMock ? 'none' : '';
            mockGroup.style.display = isMock ? '' : 'none';
            termSelect.required = !isMock;
            mockSelect.required = isMock;
        }

        scopeSelect.addEventListener('change', toggleScopeInputs);
        toggleScopeInputs();
    });
</script>
@endsection

