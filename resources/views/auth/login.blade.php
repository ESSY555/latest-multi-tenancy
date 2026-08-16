@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen flex items-start justify-center bg-white px-3 sm:px-4 pt-6 sm:pt-10" style="background-image: url('{{ asset('images/gradient.jpg') }}'); background-size: cover; background-position: center;">
    <div class="w-full max-w-2xl mb-8">
        <div class="text-center mb-4">
            <div class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white leading-tight">Bezaleel International School</div>
            <div class="mt-2 text-sm sm:text-base text-white">School Management System</div>
        </div>

        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 md:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold">Sign In</h1>
                    <p class="text-sm text-gray-600">Enter your credentials to access your account</p>
                </div>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l9-9 9 9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 21V9h6v12"/></svg>
                    <span>Home</span>
                </a>
            </div>

            @php($tab = old('role', request('role', 'admin')))

            <form method="POST" action="{{ url('/login') }}" class="mt-6 grid grid-cols-1 gap-4">
                @csrf
                <input type="hidden" name="role" id="login-role" value="{{ $tab }}" />

                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 rounded-md border bg-gray-50 text-sm overflow-hidden" role="tablist">
                        @foreach ([
    'super_admin' => ['label' => 'Super Admin', 'icon' => '<path d="M12 3l3 3-3 3-3-3 3-3z"/><path d="M4 21h16v-2a6 6 0 00-6-6H10a6 6 0 00-6 6v2z"/>'],
    'admin' => ['label' => 'Admin', 'icon' => '<path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/><path d="M4.5 19.5a7.5 7.5 0 0115 0v.75H4.5v-.75z"/>'],
    'teacher' => ['label' => 'Teacher', 'icon' => '<path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5 12.083 12.083 0 015.84 10.578L12 14z"/>'],
    'student' => ['label' => 'Student', 'icon' => '<path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M4.5 10.5v3.75A11.25 11.25 0 0012 24a11.25 11.25 0 007.5-9.75V10.5"/>'],
] as $value => $data)
                            <button type="button" data-role="{{ $value }}" class="tab-btn px-3 py-2 sm:px-4 border-r sm:last:border-r-0 odd:border-r even:border-r-0 sm:even:border-r flex items-center justify-center gap-2 {{ $tab === $value ? 'bg-white text-gray-900' : 'text-gray-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $data['icon'] !!}</svg>
                                <span class="text-xs sm:text-sm">{{ $data['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                    @error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                
                <div>
                    <label class="block text-sm font-medium mb-1">Email or Username</label>
                    <input type="text" name="credential" value="{{ old('credential', old('email')) }}" class="w-full border rounded px-3 py-2" placeholder="you@example.com or student123">
                    @error('credential')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <a class="text-xs text-blue-600" href="#">Forgot password?</a>
                    </div>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="••••••••">
                    @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Branch</label>
                    <div class="relative">
                        <select name="branch_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer shadow-sm hover:shadow-md">
                            <option value="">-- Choose a branch --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }} ({{ $branch->code }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('branch_id')
                        <p class="text-sm text-red-600 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" class="mt-3 w-full px-4 py-3 bg-blue-700 text-white rounded-md hover:bg-blue-800 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Sign In</button>
            </form>

            {{-- <div class="mt-6 text-center text-sm text-gray-700">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Sign up</a>
            </div> --}} 

            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Need help logging in?</h3>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Make sure you select the correct role that matches your account</li>
                    <li>• Teachers, Students, and Parents: Use the credentials provided by your admin</li>
                    <li>• Make sure you've selected the correct branch for your account</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const buttons = document.querySelectorAll('.tab-btn');
    const input = document.getElementById('login-role');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            input.value = btn.dataset.role;
            buttons.forEach(b => b.classList.remove('bg-white','text-gray-900'));
            buttons.forEach(b => b.classList.add('text-gray-600'));
            btn.classList.add('bg-white','text-gray-900');
            btn.classList.remove('text-gray-600');
        });
    });
</script>
@endpush
@endsection



