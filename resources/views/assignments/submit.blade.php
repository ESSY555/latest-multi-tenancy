@extends('layouts.dashboard')

@section('title', 'Submit: ' . $assignment->title)

@section('dashboard')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Submit Assignment</h1>
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-2">{{ $assignment->title }}</h2>
        <p class="text-gray-600 mb-4">Due: {{ optional($assignment->due_date)->format('Y-m-d') ?? 'N/A' }}</p>

        <form method="POST" action="{{ route('assignments.submit.store', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Answer / Notes (optional)</label>
                <textarea name="content" rows="6" class="w-full border rounded p-2">{{ old('content') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Upload Files (optional)</label>
                <input type="file" name="files[]" multiple class="w-full border rounded p-2">
                <p class="text-xs text-gray-500 mt-1">You can select multiple files. Max 15MB each.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Submit</button>
                <a href="{{ route('student.assignments') }}" class="px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection



