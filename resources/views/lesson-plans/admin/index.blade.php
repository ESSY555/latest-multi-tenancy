@extends('layouts.dashboard')

@section('title', 'Lesson Plans Management')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Lesson Plans Management</h1>
            <p class="mt-2 text-gray-600">Review and manage lesson plans from teachers</p>
        </div>

        <!-- Enhanced Lesson Plan Management Component -->
        <x-admin.lesson-plan-management 
            :lessonPlans="$lessonPlans" 
            :stats="$stats" 
            :currentRole="session('current_role')" 
        />
    </div>
</div>
@endsection

