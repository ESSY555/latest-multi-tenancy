@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Exam Timetable</h1>
            <a href="{{ route('admin.exam-timetables.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                Back to Timetables
            </a>
        </div>

        <form action="{{ route('exam-timetables.update', $timetable->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">

            <div class="rounded-md border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800">
                Current Academic Section: <span class="font-semibold">{{ $activeYear->name }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="exam_scope" class="block text-sm font-medium text-gray-700 mb-2">Timetable Scope *</label>
                    <select id="exam_scope" name="exam_scope" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="term" {{ old('exam_scope', $timetable->exam_scope) === 'term' ? 'selected' : '' }}>Term</option>
                        <option value="mock" {{ old('exam_scope', $timetable->exam_scope) === 'mock' ? 'selected' : '' }}>Mock</option>
                    </select>
                </div>

                <div id="term-group">
                    <label for="term" class="block text-sm font-medium text-gray-700 mb-2">Academic Term *</label>
                    <select id="term" name="term" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Term</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ old('term', $timetable->academic_term_id) == $term->id ? 'selected' : '' }}>
                                {{ $term->name }} - {{ $activeYear->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="mock-group" style="display: none;">
                    <label for="mock_exam_id" class="block text-sm font-medium text-gray-700 mb-2">Mock Exam *</label>
                    <select id="mock_exam_id" name="mock_exam_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Mock Exam</option>
                        @foreach($mockExams as $mockExam)
                            <option value="{{ $mockExam->id }}" {{ old('mock_exam_id', $timetable->mock_exam_id) == $mockExam->id ? 'selected' : '' }}>
                                {{ $mockExam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Exam Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date', optional($timetable->exam_date)->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $timetable->start_time ? \Carbon\Carbon::parse($timetable->start_time)->format('H:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $timetable->end_time ? \Carbon\Carbon::parse($timetable->end_time)->format('H:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select id="class" name="class" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Class</option>
                        @foreach($classes as $schoolClass)
                            <option value="{{ $schoolClass->id }}" {{ old('class', $timetable->school_class_id) == $schoolClass->id ? 'selected' : '' }}>
                                {{ $schoolClass->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">General</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject', $timetable->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                    <input type="text" id="venue" name="venue" value="{{ old('venue', $timetable->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                    <select id="teacher_id" name="teacher_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('notes', $timetable->instructions) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.exam-timetables.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Update Timetable</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scopeSelect = document.getElementById('exam_scope');
        const termGroup = document.getElementById('term-group');
        const mockGroup = document.getElementById('mock-group');
        const termSelect = document.getElementById('term');
        const mockSelect = document.getElementById('mock_exam_id');

        function toggleScope() {
            const isMock = scopeSelect.value === 'mock';
            termGroup.style.display = isMock ? 'none' : '';
            mockGroup.style.display = isMock ? '' : 'none';
            termSelect.required = !isMock;
            mockSelect.required = isMock;
        }

        scopeSelect.addEventListener('change', toggleScope);
        toggleScope();
    });
</script>
@endsection

