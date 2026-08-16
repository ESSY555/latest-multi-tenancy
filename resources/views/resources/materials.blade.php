@extends('layouts.app')

@section('title', 'Study Materials - Bezaleel')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Study Materials</h1>
                <p class="mt-2 text-gray-600">Access a wide range of study resources including PDFs, videos, presentations,
                    and worksheets.</p>
            </div>

            <!-- Study Materials Component -->
            <x-Resources.StudyMaterials />
        </div>
    </div>
@endsection
