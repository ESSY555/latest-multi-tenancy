@extends('layouts.dashboard')

@section('title', 'Class Management - Branch Admin')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.branch-class-management :classes="$classes" :currentAcademicYear="$currentAcademicYear" />
</div>
@endsection

