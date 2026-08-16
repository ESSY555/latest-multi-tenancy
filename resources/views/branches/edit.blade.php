@extends('layouts.dashboard')

@section('title', 'Edit Branch')

@section('dashboard')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit Branch</h1>
    <form method="POST" action="{{ route('branches.update', $branch->id) }}" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Branch Name</label>
            <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="w-full border rounded p-2" required>
            @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Branch Code</label>
            <input type="text" name="code" value="{{ old('code', $branch->code) }}" class="w-full border rounded p-2" required>
            @error('code')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" class="w-full border rounded p-2"
                placeholder="+234...">
            @error('phone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Address</label>
            <input type="text" name="address" value="{{ old('address', $branch->address) }}" class="w-full border rounded p-2"
                placeholder="Street address">
            @error('address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">City</label>
                <input type="text" name="city" value="{{ old('city', $branch->city) }}" class="w-full border rounded p-2">
                @error('city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">State</label>
                <input type="text" name="state" value="{{ old('state', $branch->state) }}" class="w-full border rounded p-2">
                @error('state')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Country</label>
                <input type="text" name="country" value="{{ old('country', $branch->country) }}" class="w-full border rounded p-2"
                    placeholder="Nigeria">
                @error('country')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            <a href="{{ route('branches.index') }}" class="px-4 py-2">Cancel</a>
        </div>
    </form>
</div>
@endsection

