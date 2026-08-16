@extends('layouts.dashboard')

@section('title', 'Enroll Student')

@section('dashboard')
<div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Enroll Student in {{ $class->name }}</h1>
    <form method="POST" action="{{ route('enrollments.store', $class) }}" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Student</label>
            <select name="student_id" class="w-full border rounded p-2">
                <option value="">-- Choose a student --</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                @endforeach
            </select>
            @error('student_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Enroll</button>
            <a href="{{ route('class-management') }}" class="px-4 py-2">Cancel</a>
        </div>
    </form>
</div>
@endsection




