@extends('layouts.app')

@section('title', 'Resources - Bezaleel')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Resources</h1>
                <p class="mt-2 text-gray-600">Access all educational resources including syllabus, timetables, e-library,
                    and study materials.</p>
            </div>

            <!-- Resources Component with Tabs -->
            <x-Resources.Resources />
        </div>
    </div>
@endsection
