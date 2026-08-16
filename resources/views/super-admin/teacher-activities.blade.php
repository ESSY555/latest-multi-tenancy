@extends('layouts.dashboard')

@section('title', 'Teacher Activities - Super Admin')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.teacher-activities :teacher="$teacher" />
</div>
@endsection

