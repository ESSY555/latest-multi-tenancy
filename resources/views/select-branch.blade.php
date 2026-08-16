@extends('layouts.dashboard')

@section('title', 'Select Branch')

@section('dashboard')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white rounded-md shadow p-6">
        <h1 class="text-xl font-semibold mb-4">Select Branch</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('dashboard.select-branch.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Branch</label>
                <select name="branch_id" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Choose a branch --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }} ({{ $branch->code }})</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md">Continue</button>
        </form>
    </div>
</div>
@endsection



