@extends('layouts.dashboard')

@section('dashboard')
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create New Exam Timetable</h1>
                    <a href="{{ route('admin.exam-timetables.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Timetables
                    </a>
                </div>

                <form action="{{ route('exam-timetables.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if($activeYear)
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                    @endif

                    <div class="rounded-md border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800">
                        Current Academic Section:
                        <span class="font-semibold">{{ $activeYear?->name ?? 'No section available' }}</span>
                    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Timetable Scope -->
        <div>
            <label for="exam_scope" class="block text-sm font-medium text-gray-700 mb-2">Timetable Scope *</label>
            <select id="exam_scope" name="exam_scope" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="term" {{ old('exam_scope', 'term') === 'term' ? 'selected' : '' }}>Term</option>
                <option value="mock" {{ old('exam_scope') === 'mock' ? 'selected' : '' }}>Mock</option>
            </select>
            @error('exam_scope')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Term Selection -->
        <div id="term-group">
            <label for="term" class="block text-sm font-medium text-gray-700 mb-2">Academic Term *</label>
            <select id="term" name="term"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Term</option>
                @forelse($terms as $term)
                    <option value="{{ $term->id }}" {{ old('term') == $term->id ? 'selected' : '' }}>
                        {{ $term->name }} - {{ $activeYear->name }}
                    </option>
                @empty
                    <option value="" disabled>No terms available</option>
                @endforelse
            </select>
            @error('term')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mock Selection -->
        <div id="mock-group" style="display: none;">
            <label for="mock_exam_id" class="block text-sm font-medium text-gray-700 mb-2">Mock Exam *</label>
            <select id="mock_exam_id" name="mock_exam_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Mock Exam</option>
                @forelse($mockExams as $mockExam)
                    <option value="{{ $mockExam->id }}" {{ old('mock_exam_id') == $mockExam->id ? 'selected' : '' }}>{{ $mockExam->name }}</option>
                @empty
                    <option value="" disabled>No mock exams available</option>
                @endforelse
            </select>
            @error('mock_exam_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Exam Date</label>
            <input type="date" id="date" name="date" value="{{ old('date') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Start Time -->
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
            <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- End Time -->
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
            <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Class -->
        <div>
            <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
            <select id="class" name="class" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Class</option>
                @forelse($classes as $schoolClass)
                    <option value="{{ $schoolClass->id }}" {{ old('class') == $schoolClass->id ? 'selected' : '' }}>{{ $schoolClass->name }}</option>
                @empty
                    <option value="" disabled>No classes found in this branch</option>
                @endforelse
            </select>
        </div>

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
            <select id="subject" name="subject"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">General</option>
                @forelse($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @empty
                    <option value="" disabled>No subjects found in this branch</option>
                @endforelse
            </select>
        </div>

        <!-- Venue -->
        <div>
            <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
            <input type="text" id="venue" name="venue" value="{{ old('venue') }}" placeholder="e.g., Hall A, Lab 1"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Teacher -->
        <div>
            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
            <select id="teacher_id" name="teacher_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Teacher</option>
                                    @forelse($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @empty
                                        <option value="" disabled>No teachers found in this branch</option>
                                    @endforelse
                                </select>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes
                            (Optional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Any additional information about the exam..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.exam-timetables.index') }}"
                            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Timetable
                        </button>
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

