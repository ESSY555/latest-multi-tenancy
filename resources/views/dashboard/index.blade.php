@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('dashboard')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-gray-600">@if($isSuper && !$branch) Global Overview @else Branch: {{ $branch->name }} @endif</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Branches</div><div class="text-2xl font-semibold">{{ $stats['branches'] }}</div></div>
            <div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Classes</div><div class="text-2xl font-semibold">{{ $stats['classes'] }}</div></div>
            <div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Teachers</div><div class="text-2xl font-semibold">{{ $stats['teachers'] }}</div></div>
            <div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Students</div><div class="text-2xl font-semibold">{{ $stats['students'] }}</div></div>
            <div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Results</div><div class="text-2xl font-semibold">{{ $stats['results'] }}</div></div>
            <div class="bg-white rounded shadow p-4">
                <div class="text-sm text-gray-500">Admissions</div>
                <div class="text-2xl font-semibold">{{ $stats['admissions'] ?? 0 }}</div>
                @if(isset($stats['admissions']))
                <a href="{{ route('admin.admissions.index') }}" class="text-blue-600 text-xs">View all</a>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold">Recent Classes</h2>
                    <a href="{{ route('class-management') }}" class="text-blue-600 text-sm">View all</a>
                </div>
                <div class="divide-y">
                    @forelse($recentClasses as $cls)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $cls->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $cls->grade_level }} • {{ $cls->academic_year }} @if($isSuper && $cls->branch) • {{ $cls->branch->name }} @endif</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ $cls->enrollments()->count() }} students</div>
                        </div>
                    @empty
                        <div class="py-6 text-gray-500">No classes yet.</div>
                    @endforelse
                </div>
            </div>
            @if($teacherSummary)
            <div class="bg-white rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold">Your Classes</h2>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">Classes</div>
                        <div class="text-xl font-semibold">{{ $teacherSummary['classCount'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <div class="text-xs text-gray-500">Students</div>
                        <div class="text-xl font-semibold">{{ $teacherSummary['studentTotal'] }}</div>
                    </div>
                </div>
                <div class="divide-y">
                    @foreach($teacherSummary['classes'] as $tc)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $tc->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $tc->grade_level }} • {{ $tc->academic_year }}</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ $tc->enrollments_count }} students</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection



