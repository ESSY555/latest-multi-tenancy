@extends('layouts.dashboard')

@section('title', 'Edit Mock Subject Result')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Mock Subject Result</h1>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $mockResult->student->name }} - {{ $mockResult->subject->name }} ({{ $mockResult->mockExam->name }})
                </p>
            </div>
            <a href="{{ route('result.mock-student-sheet', ['studentId' => $mockResult->student_id, 'mockExamId' => $mockResult->mock_exam_id]) }}"
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                Back to Sheet
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('result.mock-update', $mockResult) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">TEST (10)</label>
                        <input type="number" step="0.01" min="0" max="10" name="test_score"
                            value="{{ old('test_score', $mockResult->ca1) }}"
                            class="w-full border rounded-md px-3 py-2">
                        @error('test_score')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PRACTICAL (20)</label>
                        <input type="number" step="0.01" min="0" max="20" name="practical_score"
                            value="{{ old('practical_score', $mockResult->ca2) }}"
                            class="w-full border rounded-md px-3 py-2">
                        @error('practical_score')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Exam (90)</label>
                        <input type="number" step="0.01" min="0" max="90" name="exam"
                            value="{{ old('exam', $mockResult->exam) }}"
                            class="w-full border rounded-md px-3 py-2">
                        @error('exam')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

