@extends('layouts.dashboard')

@section('title', $assignment->title)

@section('dashboard')
<div class="space-y-6">
    <!-- Header / Meta -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div class="space-y-2">
                <h1 class="text-2xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                
                <!-- Teacher Name - Prominently displayed -->
                <div class="flex items-center gap-2 text-lg text-gray-700 mb-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium">Assigned by: {{ $assignment->teacher_name ?? $assignment->teacher->name }}</span>
                </div>
                
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                        {{ $assignment->schoolClass->name }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14"/></svg>
                        Due: {{ optional($assignment->due_date)->format('M d, Y') ?? 'N/A' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border {{ $assignment->is_published ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $assignment->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('assignments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md border text-sm hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
        @if($assignment->description)
            <div>
                <h2 class="font-semibold text-gray-900 mb-2">Description</h2>
                <div class="prose max-w-none text-gray-800">{!! nl2br(e($assignment->description)) !!}</div>
            </div>
        @endif

        @if($assignment->instructions)
            <div>
                <h2 class="font-semibold text-gray-900 mb-2">Instructions</h2>
                <div class="prose max-w-none text-gray-800">{!! nl2br(e($assignment->instructions)) !!}</div>
            </div>
        @endif
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Resources</h3>
                @if($assignment->attachments && $assignment->attachments->count())
                    <ul class="divide-y">
                        @foreach($assignment->attachments as $res)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-gray-800">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ $res->original_name }}</span>
                                </div>
                                <a href="{{ Storage::disk('public')->url($res->path) }}" target="_blank" class="text-blue-600 hover:underline text-sm">Download</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No attached resources.</p>
                @endif
            </div>
        </div>

        <!-- Right column -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Submission</h3>
                <div class="text-sm text-gray-700">Format: <span class="font-medium">{{ $assignment->submission_format ?? 'Not specified' }}</span></div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @if(session('current_role') === 'student')
                        <a href="{{ route('assignments.submit', $assignment) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Submit
                        </a>
                    @endif
                    @if(in_array(session('current_role'), ['teacher','admin','super_admin']))
                        <a href="{{ route('assignments.review', $assignment) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Review Submissions
                        </a>
                        @if(session('current_role') !== 'admin' && session('current_role') !== 'super_admin' ? auth()->id() === $assignment->teacher_id : true)
                            @if(!$assignment->is_published)
                                <form method="POST" action="{{ route('assignments.publish', $assignment) }}" class="inline">
                                    @csrf
                                    <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition-colors" type="submit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Publish
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('assignments.unpublish', $assignment) }}" class="inline">
                                    @csrf
                                    <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-amber-500 text-white hover:bg-amber-600 transition-colors" type="submit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Unpublish
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection



