@extends('layouts.dashboard')

@section('title', 'Create Teacher')

@section('dashboard')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Create Teacher</h1>
    <form method="POST" action="{{ route('teachers.store') }}" class="space-y-4 bg-white p-6 rounded shadow max-w-xl">
        @csrf
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2">
            @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2">
            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2">
            @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Branch</label>
            @if(auth()->user()->is_super_admin)
                {{-- Super admin can select any branch --}}
                <select name="branch_id" class="w-full border rounded p-2">
                    <option value="">-- Select Branch --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                            {{ $branch->name }} ({{ $branch->code }})
                        </option>
                    @endforeach
                </select>
            @else
                {{-- Branch admin can only assign to their own branch --}}
                @if($branches->count() > 0)
                    @php $branch = $branches->first(); @endphp
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="text" value="{{ $branch->name }} ({{ $branch->code }})" 
                           class="w-full border rounded p-2 bg-gray-100" readonly>
                    <p class="text-sm text-gray-600 mt-1">You can only assign teachers to your branch.</p>
                @else
                    <p class="text-sm text-red-600">No branch available.</p>
                @endif
            @endif
            @error('branch_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Teacher</button>
            <a href="{{ route('teachers.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection



