@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
    <div class="min-h-screen flex items-start justify-center bg-white px-4 pt-10"
        style="background-image: url('{{ asset('images/gradient.jpg') }}'); background-size: cover; background-position: center;">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-4">
                <div class="text-5xl font-extrabold text-white leading-none">Bezaleel</div>
                <div class="mt-2 text-white">Create your account</div>
            </div>

            <div class="bg-white rounded-2xl shadow border p-6 md:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold">Sign Up</h1>
                        <p class="text-sm text-gray-600">Get started with your branch</p>
                    </div>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l9-9 9 9" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 21V9h6v12" />
                        </svg>
                        <span>Home</span>
                    </a>
                </div>

                <form method="POST" action="{{ url('/register') }}" class="mt-6 grid grid-cols-1 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2"
                            placeholder="Jane Doe">
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2"
                            placeholder="you@example.com">
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Password</label>
                            <input type="password" name="password" class="w-full border rounded px-3 py-2"
                                placeholder="••••••••">
                            @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Confirm password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Branch</label>
                        <select name="branch_id" class="w-full border-gray-300 rounded-md">
                            <option value="">-- Choose a branch --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}
                                    ({{ $branch->code }})</option>
                            @endforeach
                        </select>
                        @error('branch_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <select name="role" class="w-full border-gray-300 rounded-md">
                            <option value="">-- Choose your role --</option>
                            <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            <option value="teacher" @selected(old('role') === 'teacher')>Teacher</option>
                            <option value="student" @selected(old('role') === 'student')>Student</option>
                            <option value="parent" @selected(old('role') === 'parent')>Parent</option>
                        </select>
                        @error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                        class="mt-3 w-full px-4 py-3 bg-blue-700 text-white rounded-md hover:bg-blue-800 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Create
                        account</button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-700">
                    Already have an account? <a href="{{ route('login') }}" class="text-blue-600">Sign in</a>
                </div>
            </div>
        </div>
    </div>
@endsection
