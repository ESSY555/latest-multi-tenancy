@extends('layouts.dashboard')

@section('title', 'Create Assignment')

@section('dashboard')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Create Assignment</h1>
    <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow max-w-3xl w-full mx-auto">
        @csrf
        <div>
            <label class="block text-sm font-medium">Class</label>
            <select name="school_class_id" class="w-full border rounded p-2">
                <option value="">-- Choose a class --</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
            @error('school_class_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2">
            @error('title')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Instructions</label>
            <textarea name="instructions" class="w-full border rounded p-2" rows="3">{{ old('instructions') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Submission Format</label>
            <input type="text" name="submission_format" value="{{ old('submission_format') }}" class="w-full border rounded p-2" placeholder="online upload / written / quiz">
        </div>
        <div>
            <label class="block text-sm font-medium">Due date</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Attach Resources (optional)</label>
            <input type="file" name="resources[]" multiple class="w-full border rounded p-2">
            <p class="text-xs text-gray-500 mt-1">You can select multiple files. Max 10MB each.</p>
        </div>
        <div class="flex items-center space-x-2">
            <input id="allow_late" type="checkbox" name="allow_late" value="1" class="h-4 w-4">
            <label for="allow_late" class="text-sm">Allow late submissions</label>
        </div>
        <div class="flex items-center space-x-2">
            <input id="publish" type="checkbox" name="publish" value="1" class="h-4 w-4">
            <label for="publish" class="text-sm">Publish now (notify students)</label>
        </div>
        <div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
            <a href="{{ route('assignments.index') }}" class="px-4 py-2">Cancel</a>
        </div>
    </form>
</div>
@endsection



