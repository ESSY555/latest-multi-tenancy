@extends('layouts.dashboard')

@section('title', 'Manage Teachers - Admin')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.admin-manage-teachers :teachers="$teachers" :branches="$branches" :subjects="$subjects" :classes="$classes" :currentAcademicYear="$currentAcademicYear" />
</div>
@endsection

