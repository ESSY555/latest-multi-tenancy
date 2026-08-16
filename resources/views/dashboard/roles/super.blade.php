        @extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .tab-btn {
            @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
            transition: all 0.2s ease-in-out;
        }

        .tab-btn.active-tab {
            @apply border-blue-500 text-blue-600;
        }

        .tab-btn.active-tab {
            border-bottom-color: #3b82f6;
            color: #2563eb;
        }

        .tab-panel {
            display: block;
        }

        .tab-panel.hidden {
            display: none;
        }



        /* Smooth transitions for tab content */
        .tab-panel {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('dashboard')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    @if(isset($branch))
                        <h1 class="text-3xl font-bold text-gray-900">Branch Overview: {{ $branch->name }}</h1>
                        <p class="mt-2 text-gray-600">Activities and metrics for {{ $branch->name }} branch</p>
                    @else
                        <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
                        <p class="mt-2 text-gray-600">Manage all branches, users, and system activities</p>
                    @endif
                </div>

                <!-- Super Admin Info Banner -->
                <div class="bg-gradient-to-r from-red-500 to-orange-600 rounded-lg shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="text-white">
                            <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                            <p class="text-red-100">Super Administrator</p>
                            @if(isset($branch))
                                <p class="text-red-100">{{ $branch->name }}</p>
                            @else
                                <p class="text-red-100">Global System Management</p>
                            @endif
                        </div>
                        <div class="text-right text-white">
                            @if(isset($branch))
                                <div class="text-4xl font-bold">{{ \App\Models\Branch::count() }}</div>
                                <div class="text-red-100">Total Branches</div>
                            @else
                                <div class="text-4xl font-bold">{{ \App\Models\Branch::count() }}</div>
                                <div class="text-red-100">Total Branches</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions and Branch Selection -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Academic Calendar Quick Actions -->
                        <a href="{{ route('academic-calendar.index') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center gap-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Manage Calendar
                        </a>
                        <a href="{{ route('dashboard.calendar') }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center gap-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Calendar
                        </a>

                        <!-- School News Quick Actions -->
                        <a href="{{ route('school-news.create') }}"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center gap-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create News
                        </a>
                        <a href="{{ route('school-news.admin') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center gap-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Manage News
                        </a>

                        @if(isset($branch))
                            <a href="{{ route('dashboard', ['global' => 1]) }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Global View
                            </a>
                        @endif


                        <div class="relative w-full sm:w-auto sm:ml-auto min-w-0">
                            <select
                                id="branch-selector"
                                class="w-full sm:w-64 md:w-72 max-w-full px-4 py-2 border border-gray-300 rounded-md bg-white
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors
                                       overflow-hidden text-ellipsis whitespace-nowrap"
                            >
                                <option value="">-- Select Branch --</option>

                                @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                    <option
                                        value="{{ $b->id }}"
                                        @if(isset($branch) && $branch->id == $b->id) selected @endif
                                    >
                                        {{ $b->name }} ({{ $b->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="relative w-full sm:w-auto sm:ml-auto">
                            <select id="branch-selector"
                                class="w-full sm:w-64 md:w-72 px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors truncate">
                                <option value="">-- Select Branch --</option>
                                @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                    <option value="{{ $b->id }}" @if(isset($branch) && $branch->id == $b->id) selected @endif>
                                        {{ $b->name }} ({{ $b->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>

                <!-- Management Tabs -->
                <div class="bg-white rounded-lg shadow mb-8">
                    <div class="border-b border-gray-200">
                        <nav class="flex flex-col md:flex-row gap-5 justify-between space-x-8 px-6" aria-label="Tabs">
                            <button onclick="showTab('overview')"
                                class="tab-btn py-4 px-1 border-b-2 font-medium text-sm active-tab" data-tab="overview">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Overview
                            </button>
                            <button onclick="showTab('users')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm"
                                data-tab="users">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Users
                            </button>
                            <button onclick="showTab('activities')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm"
                                data-tab="activities">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Activities
                            </button>
                            <button onclick="showTab('user-activities')"
                                class="tab-btn py-4 px-1 border-b-2 font-medium text-sm" data-tab="user-activities">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                User Activities
                            </button>
                            <button onclick="showTab('academic-monitoring')"
                                class="tab-btn py-4 px-1 border-b-2 font-medium text-sm" data-tab="academic-monitoring">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Academic Monitoring
                            </button>

                            <a href="{{ route('gallery.admin') }}" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Gallery Management
                            </a>

                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="tab-content">
                    <!-- Overview Tab -->
                    <div id="overview-tab" class="tab-panel">
                        <!-- Summary Banner -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white mb-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">
                                        @if(isset($branch))
                                            {{ $branch->name }} Branch Summary
                                        @else
                                            System Overview
                                        @endif
                                    </h2>
                                    <p class="text-blue-100">
                                        @if(isset($branch))
                                            Monitor activities, performance, and user engagement for this specific branch
                                        @else
                                            Comprehensive view of all branches, users, and system activities
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold">
                                        @if(isset($branch))
                                            {{ $stats['classes'] + $stats['teachers'] + $stats['students'] }}
                                        @else
                                            {{ $stats['branches'] + $stats['classes'] + $stats['teachers'] + $stats['students'] }}
                                        @endif
                                    </div>
                                    <div class="text-blue-100 text-sm">
                                        @if(isset($branch))
                                            Total Entities
                                        @else
                                            Total Items
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Users Statistics Section -->
                        <div class="bg-white rounded-lg shadow p-6 mb-8">
                            <h2 class="text-xl font-semibold mb-2">
                                @if(isset($branch))
                                    Branch Overview
                                @else
                                    Global Overview
                                @endif
                            </h2>
                            <p class="text-gray-500 text-sm mb-6">
                                @if(isset($branch))
                                    Total users in {{ $branch->name }} branch
                                @else
                                    Total users aggregated across all branches in the system
                                @endif
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['students'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">Total Students</div>
                                </div>
                                <div class="text-center p-4 bg-teal-50 rounded-lg">
                                    <div class="text-3xl font-bold text-teal-600">{{ $stats['teachers'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">Total Teachers</div>
                                </div>
                                <div class="text-center p-4 bg-rose-50 rounded-lg">
                                    <div class="text-3xl font-bold text-rose-600">{{ $stats['administrators'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">Total Administrators</div>
                                </div>
                            </div>
                        </div>

                        @if(isset($promotionSummary))
                            <div class="bg-white rounded-lg shadow p-6 mb-8">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">Promotion & Class Performance</h2>
                                        <p class="text-sm text-gray-500">Active session: {{ $activeAcademicYear->name ?? 'N/A' }} • {{ $activeAcademicTerm?->name ?? 'Current term' }}</p>
                                    </div>
                                    
                                    <div class="flex-grow flex md:justify-end md:mx-4">
                                        <form method="GET" action="{{ url()->current() }}" class="flex-row md:flex-row items-center gap-2">
                                            @if(request('global'))
                                                <input type="hidden" name="global" value="{{ request('global') }}">
                                            @endif
                                            <div>
                                                <h2 class="text-sm font-semibold text-gray-700 mr-2 whitespace-nowrap">Select a session:</h2>
                                            </div>
                                            <select name="session_id" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[150px]">
                                                <option value="">-- Current Active Session --</option>
                                                @if(isset($academicYears))
                                                    @foreach($academicYears as $year)
                                                        <option value="{{ $year->id }}" @if(($selectedSessionId ?? '') == $year->id) selected @endif>
                                                            {{ $year->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </form>
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        <div class="font-semibold text-gray-900">{{ $promotionSummary['promoted_count'] ?? 0 }} promoted</div>
                                        <div>{{ $promotionSummary['promoted_by_trial_count'] ?? 0 }} promoted by trial</div>
                                        <div>{{ $promotionSummary['not_promoted_count'] ?? 0 }} not promoted</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 mb-4">
                                    <div class="rounded-lg border border-green-100 bg-green-50 p-4">
                                        <div class="text-3xl font-bold text-green-700">{{ $promotionSummary['promoted_count'] ?? 0 }}</div>
                                        <div class="text-sm text-green-700">Promoted</div>
                                    </div>
                                    <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                                        <div class="text-3xl font-bold text-emerald-700">{{ $promotionSummary['promoted_by_trial_count'] ?? 0 }}</div>
                                        <div class="text-sm text-emerald-700">Promoted by Trial</div>
                                    </div>
                                    <div class="rounded-lg border border-rose-100 bg-rose-50 p-4">
                                        <div class="text-3xl font-bold text-rose-700">{{ $promotionSummary['not_promoted_count'] ?? 0 }}</div>
                                        <div class="text-sm text-rose-700">Not Promoted</div>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <div class="text-3xl font-bold text-gray-700">{{ $promotionSummary['failed_count'] ?? 0 }}</div>
                                        <div class="text-sm text-gray-700">Failed</div>
                                    </div>
                                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                                        <div class="text-3xl font-bold text-amber-700">{{ $promotionSummary['resit_count'] ?? 0 }}</div>
                                        <div class="text-sm text-amber-700">Resit</div>
                                    </div>
                                    <div class="rounded-lg border border-sky-100 bg-sky-50 p-4">
                                        <div class="text-3xl font-bold text-sky-700">{{ $promotionSummary['pending_count'] ?? 0 }}</div>
                                        <div class="text-sm text-sky-700">Pending</div>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-lg border border-gray-200">
                                    <div class="bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">Highest / Best Result Per Class</div>
                                    @if(($promotionSummary['best_per_class'] ?? collect())->isNotEmpty())
                                        <div class="divide-y divide-gray-200">
                                            @foreach($promotionSummary['best_per_class'] as $best)
                                                <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                                                    <div>
                                                        <div class="font-semibold text-gray-900">{{ $best['student_name'] ?? 'N/A' }}</div>
                                                        <div class="text-sm text-gray-500">{{ $best['class_name'] ?? 'N/A' }}</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="font-semibold text-indigo-600">{{ $best['average_score'] ?? 0 }}%</div>
                                                        <div class="text-xs uppercase tracking-wide text-gray-500">Position {{ $best['position'] ?? 1 }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="px-4 py-6 text-sm text-gray-500">No class performance data is available yet for the current session.</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Statistics Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @if(isset($branch))
                                <!-- Branch-specific stats -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Classes</div>
                                    <div class="text-2xl font-semibold">{{ $stats['classes'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Teachers</div>
                                    <div class="text-2xl font-semibold">{{ $stats['teachers'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Students</div>
                                    <div class="text-2xl font-semibold">{{ $stats['students'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Parents</div>
                                    <div class="text-2xl font-semibold">{{ $stats['parents'] }}</div>
                                </div>
                            @else
                                <!-- Global stats -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Branches</div>
                                    <div class="text-2xl font-semibold">{{ $stats['branches'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Classes</div>
                                    <div class="text-2xl font-semibold">{{ $stats['classes'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Teachers</div>
                                    <div class="text-2xl font-semibold">{{ $stats['teachers'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Students</div>
                                    <div class="text-2xl font-semibold">{{ $stats['students'] }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Stats Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
                            @if(isset($branch))
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Subjects</div>
                                    <div class="text-2xl font-semibold">{{ $stats['subjects'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Assignments</div>
                                    <div class="text-2xl font-semibold">{{ $stats['assignments'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Results</div>
                                    <div class="text-2xl font-semibold">{{ $stats['results'] }}</div>
                                </div>
                            @else
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Parents</div>
                                    <div class="text-2xl font-semibold">{{ $stats['parents'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Assignments</div>
                                    <div class="text-2xl font-semibold">{{ $stats['assignments'] }}</div>
                                </div>
                                <div class="bg-white rounded shadow p-4">
                                    <div class="text-sm text-gray-500">Results</div>
                                    <div class="text-2xl font-semibold">{{ $stats['results'] }}</div>
                                </div>
                            @endif
                        </div>

                        @if(!isset($branch) && isset($userActivitySummary))
                            <!-- User Activity Summary -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h2 class="text-xl font-semibold mb-6">User Activity Summary</h2>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                                        <div class="text-3xl font-bold text-blue-600">{{ $userActivitySummary['admins'] }}</div>
                                        <div class="text-sm text-gray-600">Admins</div>
                                        <div class="text-xs text-gray-500 mt-1">Managing branches</div>
                                    </div>
                                    <div class="text-center p-4 bg-green-50 rounded-lg">
                                        <div class="text-3xl font-bold text-green-600">{{ $userActivitySummary['teachers'] }}
                                        </div>
                                        <div class="text-sm text-gray-600">Teachers</div>
                                        <div class="text-xs text-gray-500 mt-1">Teaching classes</div>
                                    </div>
                                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                                        <div class="text-3xl font-bold text-purple-600">{{ $userActivitySummary['students'] }}
                                        </div>
                                        <div class="text-sm text-gray-600">Students</div>
                                        <div class="text-xs text-gray-500 mt-1">Learning actively</div>
                                    </div>
                                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                        <div class="text-3xl font-bold text-yellow-600">{{ $userActivitySummary['parents'] }}
                                        </div>
                                        <div class="text-sm text-gray-600">Parents</div>
                                        <div class="text-xs text-gray-500 mt-1">Monitoring progress</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Navigation to Monitoring -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h2 class="text-xl font-semibold mb-6">Quick Access to Monitoring</h2>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <button onclick="showTab('user-activities')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">User Activities</div>
                                                <div class="text-sm text-gray-500">Monitor all user actions</div>
                                            </div>
                                        </div>
                                    </button>

                                    <button onclick="showTab('academic-monitoring')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-green-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Academic Monitoring</div>
                                                <div class="text-sm text-gray-500">Track performance & progress</div>
                                            </div>
                                        </div>
                                    </button>

                                    <button onclick="showTab('activities')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-gray-900 font-medium">System Activities</div>
                                                <div class="text-sm text-gray-500">View recent system events</div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Charts Section -->
                        @if(isset($charts) && count($charts) > 0)
                            <div class="bg-white rounded-lg shadow p-6 mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Analytics & Insights</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($charts as $chart)
                                        <x-dashboard-chart :chart="$chart" />
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Content Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5 ">
                            @if(isset($branch))
                                <!-- Branch-specific content -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Classes</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentClasses as $c)
                                            <div class="py-3 flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium">{{ $c->name }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $c->grade_level }} •
                                                        {{ $c->academic_year }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Branch Users</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($branchUsers as $user)
                                            <div class="py-3 flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium">{{ $user->name }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $user->email }} •
                                                        {{ ucfirst($user->role) }}</div>
                                                </div>
                                                <span
                                                    class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ ucfirst($user->role) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Global content -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Branches</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentBranches as $b)
                                            <div class="py-3 flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium">{{ $b->name }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $b->code }}</div>
                                                </div>
                                                <button onclick="selectBranch({{ $b->id }})"
                                                    class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200">
                                                    View
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Users</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentUsers as $user)
                                            <div class="py-3 flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium">{{ $user->name }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                                </div>
                                                <div class="flex gap-1">
                                                    @foreach($user->branches as $branch)
                                                        <span
                                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">{{ ucfirst($branch->pivot->role) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Recent Admissions Section -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Admissions</h2>
                                        <a href="{{ route('admin.admissions.index') }}"
                                            class="text-blue-600 text-sm hover:text-blue-800">View all</a>
                                    </div>
                                    <div class="divide-y">
                                        @if(isset($recentAdmissions) && $recentAdmissions->count() > 0)
                                            @foreach($recentAdmissions as $admission)
                                                <div class="py-3 flex items-center justify-between hover:bg-gray-50 rounded-lg p-2 transition-colors cursor-pointer"
                                                    onclick="viewAdmission({{ $admission->id }})">
                                                    <div>
                                                        <div class="font-medium">{{ $admission->full_name }}</div>
                                                        <div class="text-gray-500 text-xs">{{ $admission->branch->name ?? 'N/A' }} •
                                                            {{ $admission->email }}</div>
                                                    </div>
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $admission->status_color }}">
                                                        {{ ucfirst($admission->status) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="py-3 text-gray-500 text-center">No recent admissions</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Content Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5">
                            @if(isset($branch))
                                <!-- Branch-specific assignments and results -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Assignments</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentAssignments as $a)
                                            <div class="py-3">
                                                <div class="font-medium">{{ $a->title }}</div>
                                                <div class="text-gray-500 text-xs">{{ $a->schoolClass->name }} •
                                                    {{ $a->teacher_name ?? $a->teacher->name }} • Due {{ $a->due_date }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Results</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentResults as $r)
                                            <div class="py-3">
                                                <div class="font-medium">{{ $r->student->name }}</div>
                                                <div class="text-gray-500 text-xs">{{ $r->schoolClass->name }} • Score:
                                                    {{ $r->score }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Global assignments and results -->
                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Assignments</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentAssignments as $a)
                                            <div class="py-3">
                                                <div class="font-medium">{{ $a->title }}</div>
                                                <div class="text-gray-500 text-xs">{{ $a->schoolClass->name }} •
                                                    {{ $a->teacher_name ?? $a->teacher->name }} •
                                                    {{ $a->schoolClass->branch->name }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white rounded shadow p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-semibold">Recent Results</h2>
                                    </div>
                                    <div class="divide-y">
                                        @foreach($recentResults as $r)
                                            <div class="py-3">
                                                <div class="font-medium">{{ $r->student->name }}</div>
                                                <div class="text-gray-500 text-xs">{{ $r->schoolClass->name }} •
                                                    {{ $r->schoolClass->branch->name }} • Score: {{ $r->score }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Actions Row -->
                        <div class="bg-white rounded shadow p-4 mt-8">
                            <h2 class="font-semibold mb-4">Quick Actions</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @if(isset($branch))
                                    <a href="{{ route('class-management') }}"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Manage Classes</div>
                                                <div class="text-sm text-gray-500">{{ $stats['classes'] }} classes</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('user-management') }}"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-green-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Teachers</div>
                                                <div class="text-sm text-gray-500">{{ $stats['teachers'] }} teachers</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('students.index') }}"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Manage Students</div>
                                                <div class="text-sm text-gray-500">{{ $stats['students'] }} students</div>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <a href="#" onclick="showTab('branches')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Manage Branches</div>
                                                <div class="text-sm text-gray-500">{{ $stats['branches'] }} branches</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="#" onclick="showTab('classes')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-green-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">View All Classes</div>
                                                <div class="text-sm text-gray-500">{{ $stats['classes'] }} classes</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="#" onclick="showTab('users')"
                                        class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">View All Users</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $stats['teachers'] + $stats['students'] + $stats['parents'] }} users
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Management Tab -->
                <div id="users-tab" class="tab-panel hidden">
                    <x-admin.user-management />
                </div>



                <!-- Activities Tab -->
                <div id="activities-tab" class="tab-panel hidden">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-6">System Activities</h2>

                        <!-- Recent Activities Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium mb-3">Recent Assignments</h3>
                                <div class="space-y-3">
                                    @foreach(\App\Models\Assignment::with(['schoolClass.branch', 'teacher'])->latest()->take(5)->get() as $assignment)
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <div class="font-medium text-sm">{{ $assignment->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $assignment->schoolClass->name }} •
                                                {{ $assignment->teacher_name ?? $assignment->teacher->name }} •
                                                {{ $assignment->schoolClass->branch->name }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h3 class="font-medium mb-3">Recent Results</h3>
                                <div class="space-y-3">
                                    @foreach(\App\Models\Result\Result::with(['student', 'schoolClass.branch'])->latest()->take(5)->get() as $result)
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <div class="font-medium text-sm">{{ $result->student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $result->schoolClass->name }} •
                                                {{ $result->schoolClass->branch->name }} • Score: {{ $result->total }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activities Tab -->
                <div id="user-activities-tab" class="tab-panel hidden">
                    <div class="space-y-6">
                        <!-- Admin Activities -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Admin Activities</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-medium mb-3">Recent Admin Actions</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\User::whereHas('branches', function ($query) {
                                                $query->where('role', 'admin');
                                            })->with(['branches'])->latest()->take(5)->get() as $admin)
                                                <div class="p-3 border border-gray-200 rounded-lg">
                                                    <div class="font-medium text-sm">{{ $admin->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $admin->branches->first()->name ?? 'No Branch' }} • Last Login:
                                                        {{ $admin->updated_at->diffForHumans() }}</div>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium mb-3">Admin Management Actions</h3>
                                    <div class="space-y-3">
                                        <div class="p-3 border border-gray-200 rounded-lg bg-blue-50">
                                            <div class="font-medium text-sm">User Management</div>
                                            <div class="text-xs text-gray-500">Teachers, Students, Parents</div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg bg-green-50">
                                            <div class="font-medium text-sm">Academic Management</div>
                                            <div class="text-xs text-gray-500">Classes, Subjects, Assignments</div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg bg-purple-50">
                                            <div class="font-medium text-sm">Calendar Management</div>
                                            <div class="text-xs text-gray-500">Academic Calendar, Events</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Activities -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Teacher Activities</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-medium mb-3">Recent Teacher Actions</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\User::whereHas('branches', function ($query) {
                                                $query->where('role', 'teacher');
                                            })->with(['branches'])->latest()->take(5)->get() as $teacher)
                                                <div class="p-3 border border-gray-200 rounded-lg">
                                                    <div class="font-medium text-sm">{{ $teacher->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $teacher->branches->first()->name ?? 'No Branch' }} • Last Active:
                                                        {{ $teacher->updated_at->diffForHumans() }}</div>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium mb-3">Teaching Activities</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\Assignment::with(['teacher', 'schoolClass.branch'])->latest()->take(5)->get() as $assignment)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <div class="font-medium text-sm">{{ $assignment->title }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $assignment->teacher_name ?? $assignment->teacher->name }} •
                                                    {{ $assignment->schoolClass->name }} •
                                                    {{ $assignment->schoolClass->branch->name }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Activities -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Student Activities</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-medium mb-3">Recent Student Actions</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\User::whereHas('branches', function ($query) {
                                                $query->where('role', 'student');
                                            })->with(['branches'])->latest()->take(5)->get() as $student)
                                                <div class="p-3 border border-gray-200 rounded-lg">
                                                    <div class="font-medium text-sm">{{ $student->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $student->branches->first()->name ?? 'No Branch' }} • Last Active:
                                                        {{ $student->updated_at->diffForHumans() }}</div>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium mb-3">Student Performance</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\Result\Result::with(['student', 'schoolClass.branch'])->latest()->take(5)->get() as $result)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <div class="font-medium text-sm">{{ $result->student->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $result->schoolClass->name }} •
                                                    {{ $result->schoolClass->branch->name }} • Score: {{ $result->total }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parent Activities -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Parent Activities</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-medium mb-3">Recent Parent Actions</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\User::whereHas('branches', function ($query) {
                                                $query->where('role', 'parent');
                                            })->with(['branches'])->latest()->take(5)->get() as $parent)
                                                <div class="p-3 border border-gray-200 rounded-lg">
                                                    <div class="font-medium text-sm">{{ $parent->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $parent->branches->first()->name ?? 'No Branch' }} • Last Active:
                                                        {{ $parent->updated_at->diffForHumans() }}</div>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium mb-3">Parent Engagement</h3>
                                    <div class="space-y-3">
                                        <div class="p-3 border border-gray-200 rounded-lg bg-yellow-50">
                                            <div class="font-medium text-sm">Children Monitoring</div>
                                            <div class="text-xs text-gray-500">Viewing grades, attendance, assignments</div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg bg-blue-50">
                                            <div class="font-medium text-sm">Academic Calendar</div>
                                            <div class="text-xs text-gray-500">Checking school events and holidays</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Monitoring Tab -->
                <div id="academic-monitoring-tab" class="tab-panel hidden">
                    <div class="space-y-6">
                        <!-- Academic Performance Overview -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Academic Performance Overview</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ \App\Models\Result\Result::count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Results</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\Assignment::count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Assignments</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-purple-600">{{ \App\Models\Attendance::count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Attendance Records</div>
                                </div>
                            </div>
                        </div>

                        <!-- Branch Performance Comparison -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Branch Performance Comparison</h2>
                            <div class="space-y-4">
                                @foreach(\App\Models\Branch::withCount(['classes', 'users'])->get() as $branch)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <div class="font-medium">{{ $branch->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $branch->classes_count }} Classes •
                                                {{ $branch->users_count }} Users</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-semibold text-blue-600">
                                                @php
                                                    $avgScore = \App\Models\Result\Result::whereHas('schoolClass', function ($q) use ($branch) {
                                                        $q->where('branch_id', $branch->id);
                                                    })->avg('total');
                                                @endphp
                                                {{ $avgScore ? number_format($avgScore, 1) : 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">Avg Score</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Academic Events -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Recent Academic Events</h2>
                            <div class="space-y-4">
                                @foreach(\App\Models\AcademicEvent::with(['academicYear.branch'])->latest()->take(10)->get() as $event)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <div class="font-medium">{{ $event->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $event->academicYear->branch->name }} •
                                                {{ $event->event_type }} • {{ $event->start_date->format('M d, Y') }}</div>
                                        </div>
                                        <span
                                            class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ ucfirst($event->event_type) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Attendance Monitoring -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold mb-6">Attendance Monitoring</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-medium mb-3">Recent Attendance Records</h3>
                                    <div class="space-y-3">
                                        @foreach(\App\Models\Attendance::with(['student', 'schoolClass.branch'])->latest()->take(5)->get() as $attendance)
                                            <div class="p-3 border border-gray-200 rounded-lg">
                                                <div class="font-medium text-sm">{{ $attendance->student->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $attendance->schoolClass->name }} •
                                                    {{ $attendance->schoolClass->branch->name }} • {{ $attendance->status }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium mb-3">Attendance Statistics</h3>
                                    <div class="space-y-3">
                                        <div class="p-3 border border-gray-200 rounded-lg bg-green-50">
                                            <div class="font-medium text-sm">Present</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \App\Models\Attendance::where('status', 'present')->count() }} records
                                            </div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg bg-red-50">
                                            <div class="font-medium text-sm">Absent</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \App\Models\Attendance::where('status', 'absent')->count() }} records
                                            </div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg bg-yellow-50">
                                            <div class="font-medium text-sm">Late</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \App\Models\Attendance::where('status', 'late')->count() }} records</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    </div>
    </div>
    </div>
    </div>
    </div>

    @push('scripts')
        <script>
            // Tab management
            function showTab(tabName) {
                // Hide all tab panels
                const tabPanels = document.querySelectorAll('.tab-panel');
                tabPanels.forEach(panel => panel.classList.add('hidden'));

                // Remove active class from all tab buttons
                const tabButtons = document.querySelectorAll('.tab-btn');
                tabButtons.forEach(btn => {
                    btn.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected tab panel
                const selectedPanel = document.getElementById(tabName + '-tab');
                if (selectedPanel) {
                    selectedPanel.classList.remove('hidden');
                }

                // Activate selected tab button
                const selectedButton = document.querySelector(`[data-tab="${tabName}"]`);
                if (selectedButton) {
                    selectedButton.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                    selectedButton.classList.remove('border-transparent', 'text-gray-500');
                }
            }

            // Branch management functions
            function showCreateBranchForm() {
                document.getElementById('create-branch-form').classList.remove('hidden');
            }

            function hideCreateBranchForm() {
                document.getElementById('create-branch-form').classList.add('hidden');
            }

            function viewBranch(branchId) {
                // Set the branch in session and redirect to dashboard
                fetch('{{ route("dashboard.select-branch.post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ branch_id: branchId })
                }).then(() => {
                    window.location.href = '{{ route("dashboard") }}';
                });
            }

            // Test function to verify JavaScript is working
            function testFunction() {
                console.log('Test function called!');
                alert('JavaScript is working!');
            }



            // Test function to check password fields
            function testPasswordFields() {
                console.log('Testing password fields...');

                // Check if form exists
                const form = document.getElementById('create-user-form');
                console.log('Create user form:', form);

                // Check if password fields exist
                const passwordField = document.getElementById('user-password');
                const passwordConfirmField = document.getElementById('user-password-confirm');
                console.log('Password field:', passwordField);
                console.log('Password confirm field:', passwordConfirmField);

                // Check form visibility
                if (form) {
                    console.log('Form classes:', form.className);
                    console.log('Form display style:', form.style.display);
                    console.log('Form computed display:', window.getComputedStyle(form).display);
                    console.log('Form hidden class:', form.classList.contains('hidden'));
                }

                // Try to show the form
                if (form) {
                    form.classList.remove('hidden');
                    form.style.display = 'block';
                    console.log('Form should now be visible');
                }

                alert('Check console for password field details');
            }







            // Class management functions
            function showCreateClassForm() {
                // This would show a class creation form
                alert('Class creation form would be implemented here');
            }

            // Additional management functions
            function editBranch(branchId) {
                // This would open branch editing
                alert('Branch editing would be implemented here for branch ID: ' + branchId);
            }

            function deleteBranch(branchId) {
                if (confirm('Are you sure you want to delete this branch?')) {
                    // This would delete the branch
                    alert('Branch deletion would be implemented here for branch ID: ' + branchId);
                }
            }





            // Branch selector functionality
            document.getElementById('branch-selector').addEventListener('change', function () {
                const branchId = this.value;
                if (branchId) {
                    // Set the branch in session and redirect to dashboard
                    fetch('{{ route("dashboard.select-branch.post") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ branch_id: branchId })
                    }).then(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    });
                }
            });

            function selectBranch(branchId) {
                document.getElementById('branch-selector').value = branchId;
                document.getElementById('branch-selector').dispatchEvent(new Event('change'));
            }

            // Initialize dashboard
            document.addEventListener('DOMContentLoaded', function () {
                // Show overview tab by default
                showTab('overview');

                // Add hover effects to stat cards
                const statCards = document.querySelectorAll('.bg-white.rounded.shadow');
                statCards.forEach(card => {
                    card.addEventListener('mouseenter', function () {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                    });

                    card.addEventListener('mouseleave', function () {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                    });
                });


            });



            // Function to show Users tab from sidebar
            function showUsersTab() {
                showTab('users');
            }

            // Admission management functions
            function viewAdmission(admissionId) {
                console.log('Fetching admission details for ID:', admissionId);

                // Fetch admission details and show modal
                fetch(`/admin/admissions/${admissionId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        if (data.success) {
                            showAdmissionModal(data.admission);
                        } else {
                            alert('Error loading admission details: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching admission details:', error);
                        alert('Error loading admission details: ' + error.message);
                    });
            }

            function showAdmissionModal(admission) {
                // Populate modal with admission data
                document.getElementById('admission-name').textContent = admission.full_name;
                document.getElementById('admission-email').textContent = admission.email;
                document.getElementById('admission-phone').textContent = admission.phone_number;
                document.getElementById('admission-branch').textContent = admission.branch?.name || 'N/A';
                document.getElementById('admission-grade').textContent = admission.current_grade || 'N/A';
                document.getElementById('admission-status').textContent = admission.status;
                document.getElementById('admission-status').className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${admission.status_color}`;
                document.getElementById('admission-dob').textContent = admission.date_of_birth;
                document.getElementById('admission-gender').textContent = admission.gender;
                document.getElementById('admission-contact').textContent = admission.primary_contact_name;
                document.getElementById('admission-relationship').textContent = admission.relationship;
                document.getElementById('admission-address').textContent = admission.address;
                document.getElementById('admission-hear-about').textContent = admission.hear_about_school || 'N/A';
                document.getElementById('admission-additional').textContent = admission.additional_info || 'N/A';
                document.getElementById('admission-created').textContent = admission.created_at;

                // Set current status for form
                document.getElementById('admission-status-select').value = admission.status;
                document.getElementById('admin-notes').value = admission.admin_notes || '';
                document.getElementById('admission-id').value = admission.id;

                // Show modal
                document.getElementById('admission-modal').classList.remove('hidden');
            }

            function hideAdmissionModal() {
                document.getElementById('admission-modal').classList.add('hidden');
            }

            function hideEditUserModal() {
                document.getElementById('edit-user-modal').classList.add('hidden');
            }

            function updateUser() {
                const userId = document.getElementById('edit-user-id').value;
                const name = document.getElementById('edit-user-name').value.trim();
                const email = document.getElementById('edit-user-email').value.trim();
                const role = document.getElementById('edit-user-role').value;
                const branchId = document.getElementById('edit-user-branch').value;
                const password = document.getElementById('edit-user-password').value;
                const passwordConfirm = document.getElementById('edit-user-password-confirm').value;

                // Validate required fields
                if (!name || !email || !role || !branchId) {
                    const errorMsg = 'Name, email, role, and branch are required';
                    console.error('Validation Error:', errorMsg);
                    showMessage(errorMsg, 'error');
                    return;
                }

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    const errorMsg = 'Please enter a valid email address';
                    console.error('Validation Error:', errorMsg);
                    showMessage(errorMsg, 'error');
                    return;
                }

                // Validate password confirmation if password is provided
                if (password && password !== passwordConfirm) {
                    const errorMsg = 'Password confirmation does not match';
                    console.error('Validation Error:', errorMsg);
                    showMessage(errorMsg, 'error');
                    return;
                }

                // Validate password length if password is provided (matching signup requirements)
                if (password && password.length < 6) {
                    const errorMsg = 'Password must be at least 6 characters long';
                    console.error('Validation Error:', errorMsg);
                    showMessage(errorMsg, 'error');
                    return;
                }

                const formData = {
                    name: name,
                    email: email,
                    role: role,
                    branch_id: branchId,
                    password: password,
                    password_confirmation: passwordConfirm
                };

                fetch(`/users/${userId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showMessage('User updated successfully!', 'success');
                            hideEditUserModal();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            const errorMsg = data.message || 'Unknown error occurred';
                            console.error('Server Error:', errorMsg);
                            showMessage('Error updating user: ' + errorMsg, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Update Fetch Error:', error);
                        const errorMsg = 'Network error or server unavailable';
                        showMessage('Error updating user: ' + errorMsg, 'error');
                    });
            }

            function updateAdmissionStatus() {
                const admissionId = document.getElementById('admission-id').value;
                const status = document.getElementById('admission-status-select').value;
                const notes = document.getElementById('admin-notes').value;

                fetch(`/admin/admissions/${admissionId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status, admin_notes: notes })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Admission status updated successfully!');
                            hideAdmissionModal();
                            // Refresh the page to show updated data
                            window.location.reload();
                        } else {
                            alert('Error updating admission status: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating admission status');
                    });
            }

            // Admission Detail Modal
            document.addEventListener('DOMContentLoaded', function () {
                // Add modal HTML to the page
                const modalHTML = `
                <div id="admission-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-10 mx-auto p-6 w-11/12 max-w-3xl shadow-2xl rounded-2xl bg-white border border-gray-200">
                        <div class="relative">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Admission Application Details</h3>
                                </div>
                                <button onclick="hideAdmissionModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Admission Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Student Information -->
                                <div class="bg-gray-50 p-6 rounded-xl">
                                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Student Information
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Full Name</label>
                                            <p id="admission-name" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                                            <p id="admission-email" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone</label>
                                            <p id="admission-phone" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Date of Birth</label>
                                            <p id="admission-dob" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Gender</label>
                                            <p id="admission-gender" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Grade</label>
                                            <p id="admission-grade" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact & Additional Information -->
                                <div class="bg-gray-50 p-6 rounded-xl">
                                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Contact & Additional Info
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Primary Contact</label>
                                            <p id="admission-contact" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Relationship</label>
                                            <p id="admission-relationship" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Address</label>
                                            <p id="admission-address" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Branch</label>
                                            <p id="admission-branch" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">How did you hear about us?</label>
                                            <p id="admission-hear-about" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="mb-8">
                                <div class="bg-gray-50 p-6 rounded-xl">
                                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Additional Information
                                    </h4>
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Additional Notes</label>
                                        <p id="admission-additional" class="text-gray-900 font-medium mt-1"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Information -->
                            <div class="mb-8">
                                <div class="bg-gray-50 p-6 rounded-xl">
                                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Application Status
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Status</label>
                                            <p id="admission-status" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1"></p>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted On</label>
                                            <p id="admission-created" class="text-gray-900 font-medium mt-1"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Update Form -->
                            <div class="border-t pt-8">
                                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                                    <h4 class="font-bold text-blue-900 text-lg mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Update Application Status
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label for="admission-status-select" class="block text-sm font-semibold text-blue-700 mb-2">New Status</label>
                                            <select id="admission-status-select" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <option value="pending">⏳ Pending</option>
                                                <option value="reviewed">👀 Reviewed</option>
                                                <option value="approved">✅ Approved</option>
                                                <option value="rejected">❌ Rejected</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="admin-notes" class="block text-sm font-semibold text-blue-700 mb-2">Admin Notes</label>
                                            <textarea id="admin-notes" rows="3" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Add notes about this application..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Hidden input for admission ID -->
                                    <input type="hidden" id="admission-id">

                                    <!-- Action Buttons -->
                                    <div class="flex justify-end space-x-4">
                                        <button onclick="hideAdmissionModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                            Cancel
                                        </button>
                                        <button onclick="updateAdmissionStatus()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                                            Update Status
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Append modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);

                // Add edit user modal HTML
                const editUserModalHTML = `
                <div id="edit-user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-10 mx-auto p-6 w-11/12 max-w-2xl shadow-2xl rounded-2xl bg-white border border-gray-200">
                        <div class="relative">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Edit User</h3>
                                </div>
                                <button onclick="hideEditUserModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Edit User Form -->
                            <form id="edit-user-form" class="space-y-6">
                                <input type="hidden" id="edit-user-id">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit-user-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" id="edit-user-name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                    </div>
                                    <div>
                                        <label for="edit-user-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" id="edit-user-email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit-user-role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                        <select id="edit-user-role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                            <option value="">Select role</option>
                                            <option value="super_admin">Super Admin</option>
                                            <option value="admin">Admin</option>
                                            <option value="teacher">Teacher</option>
                                            <option value="student">Student</option>
                                            <option value="parent">Parent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="edit-user-branch" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                                        <select id="edit-user-branch" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                            <option value="">Select branch</option>
                                            @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="edit-user-password" class="block text-sm font-medium text-gray-700 mb-2">New Password (leave blank to keep current)</label>
                                    <input type="password" id="edit-user-password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Enter new password">
                                </div>

                                <div>
                                    <label for="edit-user-password-confirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" id="edit-user-password-confirm" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Confirm new password">
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                                    <button type="button" onclick="hideEditUserModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                                        Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

                // Append edit user modal to body
                document.body.insertAdjacentHTML('beforeend', editUserModalHTML);

                // Add form submission handler
                document.getElementById('edit-user-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    updateUser();
                });
            });
        </script>
    @endpush
@endsection
