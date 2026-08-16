@extends('layouts.dashboard')

@section('title', 'User Management - Admin')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <x-admin.admin-user-management :users="$users" />
</div>
@endsection

