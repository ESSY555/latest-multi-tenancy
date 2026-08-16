@extends('layouts.dashboard')

@section('title', 'Class Management - Super Admin')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.class-management :currentAcademicYear="$currentAcademicYear" />
</div>
@endsection

