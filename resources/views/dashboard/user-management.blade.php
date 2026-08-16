@extends('layouts.dashboard')

@section('title', 'Teachers Management')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.user-management :users="$users" />
</div>
@endsection

