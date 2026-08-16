@extends('layouts.app')

@section('title', 'Exam Timetables - Bezaleel')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Exam Timetables</h1>
                <p class="mt-2 text-gray-600">View and download examination schedules for all academic periods and subjects.
                </p>
            </div>

            <!-- Exam Timetables Component -->
            <x-Resources.ExamTimetables />
        </div>
    </div>
@endsection
