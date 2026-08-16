@extends('layouts.dashboard')

@section('title', 'Edit Assignment')

@section('dashboard')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Edit Assignment</h1>
    <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="space-y-4 bg-white p-6 rounded shadow max-w-3xl w-full mx-auto">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Class</label>
            <select name="school_class_id" class="w-full border rounded p-2">
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" {{ $assignment->school_class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description', $assignment->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Instructions</label>
            <textarea name="instructions" class="w-full border rounded p-2" rows="3">{{ old('instructions', $assignment->instructions) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Submission Format</label>
            <input type="text" name="submission_format" value="{{ old('submission_format', $assignment->submission_format) }}" class="w-full border rounded p-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Due date</label>
            <input type="date" name="due_date" value="{{ optional($assignment->due_date)->format('Y-m-d') }}" class="w-full border rounded p-2">
        </div>
        <div class="flex items-center space-x-2">
            <input id="allow_late" type="checkbox" name="allow_late" value="1" class="h-4 w-4" {{ $assignment->allow_late ? 'checked' : '' }}>
            <label for="allow_late" class="text-sm">Allow late submissions</label>
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Save</button>
            <a href="{{ route('assignments.show', $assignment) }}" class="px-4 py-2">Cancel</a>
        </div>
    </form>
</div>
@endsection



