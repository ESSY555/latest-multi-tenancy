@extends('layouts.dashboard')

@section('title', 'Edit Result')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Edit Result</h1>
            <a href="{{ route('result.show', $result) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">Back</a>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('result.update', $result) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                        <input type="text" value="{{ $result->student->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <input type="text" value="{{ $result->schoolClass->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" value="{{ $result->subject->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                        <input type="text" value="{{ $result->academicTerm->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ca1" class="block text-sm font-medium text-gray-700 mb-2">CA1 (0-10)</label>
                        <input type="number" name="ca1" id="ca1" step="0.1" min="0" max="10" value="{{ old('ca1', $result->ca1) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('ca1')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ca2" class="block text-sm font-medium text-gray-700 mb-2">CA2 (0-10)</label>
                        <input type="number" name="ca2" id="ca2" step="0.1" min="0" max="10" value="{{ old('ca2', $result->ca2) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('ca2')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ca3" class="block text-sm font-medium text-gray-700 mb-2">CA3 (0-10)</label>
                        <input type="number" name="ca3" id="ca3" step="0.1" min="0" max="10" value="{{ old('ca3', $result->ca3) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('ca3')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="exam" class="block text-sm font-medium text-gray-700 mb-2">Exam (0-70)</label>
                        <input type="number" name="exam" id="exam" step="0.1" min="0" max="70" value="{{ old('exam', $result->exam) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('exam')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="attendance_present" class="block text-sm font-medium text-gray-700 mb-2">Days Present</label>
                        <input type="number" name="attendance_present" id="attendance_present" min="0" value="{{ old('attendance_present', $result->attendance_present) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="attendance_absent" class="block text-sm font-medium text-gray-700 mb-2">Days Absent</label>
                        <input type="number" name="attendance_absent" id="attendance_absent" min="0" value="{{ old('attendance_absent', $result->attendance_absent) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="attendance_late" class="block text-sm font-medium text-gray-700 mb-2">Days Late</label>
                        <input type="number" name="attendance_late" id="attendance_late" min="0" value="{{ old('attendance_late', $result->attendance_late) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 cursor-pointer">Update Result</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



