                                @extends('layouts.dashboard')

@section('title', 'Student Profile - ' . $student->name)

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $student->name }}</h1>
                        <p class="text-gray-600 mt-2">Student Profile - {{ $branch->name }}</p>
                    </div>
                    <div class="flex space-x-3">
                            @if(auth()->user() && auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.students.edit', $student) }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Edit Student
                                </a>
                            @endif
                        <a href="{{ route('admin.students.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Back to Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Information Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-32 h-40 bg-green-50 rounded-lg border-2 border-gray-200 flex items-center justify-center mx-auto mb-4 overflow-hidden shadow-sm">
                            @if($student->profile_photo)
                                <img src="{{ asset('uploads/profile-photos/' . $student->profile_photo) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.523 18.246 19 16.5 19c-1.746 0-3.332-.477-4.5-1.253"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $student->name }}</h3>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">{{ $studentProfile->admission_number ?? 'Student Index' }}</p>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Admission No:</span>
                            <p class="text-gray-900 font-bold">{{ $studentProfile->admission_number ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <p class="text-gray-900">{{ $student->email }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Phone:</span>
                            <p class="text-gray-900">{{ $student->phone ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Branch:</span>
                            <p class="text-gray-900">{{ $branch->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Grade Level:</span>
                            <p class="text-gray-900">{{ $studentProfile->grade_level ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Date of Birth:</span>
                            <p class="text-gray-900">{{ $studentProfile->date_of_birth ? $studentProfile->date_of_birth->format('M j, Y') : 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Gender:</span>
                            <p class="text-gray-900">{{ $studentProfile->gender ? ucfirst($studentProfile->gender) : 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Parent:</span>
                            <p class="text-gray-900">{{ $studentProfile->parent_name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Parent Contact:</span>
                            <p class="text-gray-900">{{ $studentProfile->parent_phone ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Classes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Assignments</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_assignments'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Average Score</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['average_score'] }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Attendance</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['attendance_rate'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Tabs -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="showTab('classes')" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600" data-tab="classes">
                            Classes ({{ $stats['total_classes'] }})
                        </button>
                        <button onclick="showTab('assignments')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="assignments">
                            Assignments ({{ $stats['total_assignments'] }})
                        </button>
                        <button onclick="showTab('results')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="results">
                            Results ({{ $stats['total_results'] }})
                        </button>
                        <button onclick="showTab('attendance')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="attendance">
                            Attendance
                        </button>
                        <button onclick="showTab('activities')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="activities">
                            Recent Activities
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Classes Tab -->
                    <div id="classes-tab" class="tab-content">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Enrolled Classes</h3>
                            @if($enrollments->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($enrollments as $enrollment)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900">{{ $enrollment->schoolClass->name }}</h4>
                                                <span class="text-sm text-gray-500">{{ $enrollment->schoolClass->grade_level }}</span>
                                            </div>
                                            <div class="space-y-2 text-sm">
                                                <p><span class="text-gray-600">Subject:</span> {{ $enrollment->schoolClass->subjects->first()->name ?? 'N/A' }}</p>
                                                <p><span class="text-gray-600">Teacher:</span> {{ $enrollment->schoolClass->teachers->first()->name ?? 'N/A' }}</p>
                                                <p><span class="text-gray-600">Enrolled:</span> {{ $enrollment->created_at->format('M j, Y') }}</p>
                                                <p><span class="text-gray-600">Schedule:</span> {{ $enrollment->schoolClass->schedule ?? 'Not set' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No classes enrolled yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Assignments Tab -->
                    <div id="assignments-tab" class="tab-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assignments</h3>
                            @if($assignments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($assignments as $assignment)
                                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                                <div class="flex items-center justify-between mb-3">
                                                                    <h4 class="font-semibold text-gray-900">{{ $assignment->title }}</h4>
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                                        {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                        ($assignment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                                        {{ ucfirst($assignment->status ?? 'Not started') }}
                                                                    </span>
                                                                </div>
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                                    <p><span class="text-gray-600">Class:</span> {{ $assignment->schoolClass->name ?? 'N/A' }}</p>
                                                                    <p><span class="text-gray-600">Subject:</span> {{ $assignment->schoolClass->subjects->first()->name ?? 'N/A' }}</p>
                                                                    <p><span class="text-gray-600">Due Date:</span> {{ $assignment->due_date ? $assignment->due_date->format('M j, Y') : 'No due date' }}</p>
                                                                </div>
                                                                @if($assignment->description)
                                                                    <p class="text-sm text-gray-600 mt-2">{{ Str::limit($assignment->description, 150) }}</p>
                                                                @endif
                                                            </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No assignments found.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Results Tab -->
                    <div id="results-tab" class="tab-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Results</h3>
                            @if($results->count() > 0)
                                <div class="space-y-4">
                                    @foreach($results as $result)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900">{{ $result->subject->name ?? 'N/A' }}</h4>
                                                <span class="text-lg font-bold text-gray-900">{{ $result->total }}%</span>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                <p><span class="text-gray-600">Class:</span> {{ $result->schoolClass->name ?? 'N/A' }}</p>
                                                <p><span class="text-gray-600">Type:</span> {{ ucfirst($result->exam_type ?? 'N/A') }}</p>
                                                <p><span class="text-gray-600">Date:</span> {{ $result->created_at->format('M j, Y') }}</p>
                                            </div>
                                            @if($result->remarks)
                                                <p class="text-sm text-gray-600 mt-2"><span class="text-gray-600">Remarks:</span> {{ $result->remarks }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No results found yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div id="attendance-tab" class="tab-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Records</h3>
                            @if($attendanceRecords->count() > 0)
                                <div class="space-y-4">
                                    @foreach($attendanceRecords as $attendance)
                                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                                <div class="flex items-center justify-between mb-3">
                                                                    <h4 class="font-semibold text-gray-900">{{ $attendance->schoolClass->name ?? 'N/A' }}</h4>
                                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' :
                                        ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                                        {{ ucfirst($attendance->status) }}
                                                                    </span>
                                                                </div>
                                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                                    <p><span class="text-gray-600">Teacher:</span> {{ $attendance->schoolClass->teachers->first()->name ?? 'N/A' }}</p>
                                                                    <p><span class="text-gray-600">Date:</span> {{ $attendance->date ? $attendance->date->format('M j, Y') : 'N/A' }}</p>
                                                                    <p><span class="text-gray-600">Time:</span> {{ $attendance->created_at->format('g:i A') }}</p>
                                                                </div>
                                                                @if($attendance->remarks)
                                                                    <p class="text-sm text-gray-600 mt-2"><span class="text-gray-600">Remarks:</span> {{ $attendance->remarks }}</p>
                                                                @endif
                                                            </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No attendance records found.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Activities Tab -->
                    <div id="activities-tab" class="tab-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
                            @if($recentActivities->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentActivities as $activity)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                    @if($activity['type'] === 'enrollment')
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                            </svg>
                                                        </div>
                                                    @elseif($activity['type'] === 'result')
                                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $activity['title'] }}</h4>
                                                        <p class="text-sm text-gray-500">{{ ucfirst($activity['type']) }} - {{ ucfirst($activity['status']) }}</p>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $activity['date']->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No recent activities.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Activate selected tab button
            const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-500', 'text-blue-600');
        }

        // Initialize with classes tab
        document.addEventListener('DOMContentLoaded', function() {
            showTab('classes');
        });
        </script>
    @endpush

@endsection

