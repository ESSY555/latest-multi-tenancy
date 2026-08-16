@props(['teacher' => null])

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif
    <!-- Header Section -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Teacher Activities Dashboard</h2>
                <p class="text-gray-600 mt-1">Manage and monitor teacher activities, classes, and performance</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Teacher Information Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border border-blue-200">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $teacher->name ?? 'Teacher Name' }}</h3>
                <p class="text-gray-600">{{ $teacher->email ?? 'teacher@email.com' }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Teacher</span>
                    @if($teacher && $teacher->branches->count() > 0)
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            {{ $teacher->branches->first()->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Assigned Classes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teacher->teachingClasses->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Lesson Plans</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teacher->lessonPlans->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Assignments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teacher->teacherAssignments->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $teacher->teachingClasses->sum(function($class) { return $class->enrollments->count(); }) ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button onclick="showTab('classes')" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                Assigned Classes
            </button>
            <button onclick="showTab('class-management')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Class Management
            </button>
            <button onclick="showTab('subjects')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Subjects Taught
            </button>
            <button onclick="showTab('students')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Students
            </button>
            <button onclick="showTab('assignments')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Assignments
            </button>
            <button onclick="showTab('lesson-plans')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Lesson Plans
            </button>
            <button onclick="showTab('performance')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Performance
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div id="classes-tab" class="tab-content">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Assigned Classes</h3>
                <p class="text-sm text-gray-500">Classes currently assigned to this teacher</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($teacher->teachingClasses ?? [] as $class)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $class->branch->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $class->enrollments->count() ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($class->subjects ?? [] as $subject)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $subject->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900">View Details</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No classes assigned yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="class-management-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Class Management</h3>
                <p class="text-sm text-gray-500">Assign or unassign classes to this teacher</p>
            </div>
            <div class="p-6">
                <!-- Assign New Class Section -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Assign New Class</h4>
                    <form action="{{ route('super-admin.assign-class', $teacher->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                                <select id="branch_id" name="branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Branch</option>
                                    @foreach(\App\Models\Branch::orderBy('name')->get() as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                                <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Assign Class
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Currently Assigned Classes -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Currently Assigned Classes</h4>
                    @if($teacher->teachingClasses->count() > 0)
                        <div class="space-y-3">
                            @foreach($teacher->teachingClasses as $class)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $class->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $class->branch->name ?? 'N/A' }} • {{ $class->enrollments->count() }} students</p>
                                    </div>
                                    <form action="{{ route('super-admin.unassign-class', $teacher->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <button type="submit" onclick="return confirm('Are you sure you want to unassign this class from {{ $teacher->name }}?')" class="px-3 py-1 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                            Unassign
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            No classes currently assigned to this teacher
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="subjects-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Subjects Taught</h3>
                <p class="text-sm text-gray-500">Subjects this teacher is qualified to teach</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($teacher->subjects ?? [] as $subject)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $subject->description ?? 'No description available' }}</p>
                    </div>
                    @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        No subjects assigned yet
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="students-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Students in Assigned Classes</h3>
                <p class="text-sm text-gray-500">All students across all classes assigned to this teacher</p>
            </div>
            <div class="p-6">
                @if($teacher->teachingClasses->count() > 0)
                    <!-- Filters and Search -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="class-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Class</label>
                            <select id="class-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Classes</option>
                                @foreach($teacher->teachingClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->branch->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="student-search" class="block text-sm font-medium text-gray-700 mb-2">Search Students</label>
                            <input type="text" id="student-search" placeholder="Search by name or email..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="students-table-body">
                                @foreach($teacher->teachingClasses as $class)
                                    @foreach($class->enrollments as $enrollment)
                                        <tr class="student-row" data-class="{{ $class->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->name ?? 'N/A' }}</div>
                                                        <div class="text-sm text-gray-500">{{ $enrollment->student->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                                <div class="text-sm text-gray-500">Grade {{ $class->grade_level ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $class->branch->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $enrollment->created_at ? \Carbon\Carbon::parse($enrollment->created_at)->format('M d, Y') : 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">View Profile</button>
                                                <button class="text-blue-600 hover:text-blue-900">View Grades</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-blue-600">Total Students</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $teacher->teachingClasses->sum(function($class) { return $class->enrollments->count(); }) }}</div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-600">Classes</div>
                            <div class="text-2xl font-bold text-green-900">{{ $teacher->teachingClasses->count() }}</div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-purple-600">Branches</div>
                            <div class="text-2xl font-bold text-purple-900">{{ $teacher->teachingClasses->pluck('branch_id')->unique()->count() }}</div>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-orange-600">Avg Students/Class</div>
                            <div class="text-2xl font-bold text-orange-900">
                                {{ $teacher->teachingClasses->count() > 0 ? round($teacher->teachingClasses->sum(function($class) { return $class->enrollments->count(); }) / $teacher->teachingClasses->count(), 1) : 0 }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Students Found</h3>
                        <p class="text-gray-500">This teacher doesn't have any assigned classes yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="assignments-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Assignments</h3>
                <p class="text-sm text-gray-500">Assignments created by this teacher</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($teacher->teacherAssignments ?? [] as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $assignment->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $assignment->schoolClass->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $assignment->due_date && \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $assignment->due_date && \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Active' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No assignments created yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="lesson-plans-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Lesson Plans</h3>
                <p class="text-sm text-gray-500">Lesson plans created by this teacher</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($teacher->lessonPlans ?? [] as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $plan->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $plan->subject ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($plan->created_at)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No lesson plans created yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="performance-tab" class="tab-content hidden">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Metrics</h3>
                <p class="text-sm text-gray-500">Teacher performance and activity metrics</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Activity Overview</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Classes Taught</span>
                                <span class="text-sm font-medium">{{ $teacher->teachingClasses->count() ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Students</span>
                                <span class="text-sm font-medium">{{ $teacher->teachingClasses->sum(function($class) { return $class->enrollments->count(); }) ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Assignments Created</span>
                                <span class="text-sm font-medium">{{ $teacher->teacherAssignments->count() ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Lesson Plans</span>
                                <span class="text-sm font-medium">{{ $teacher->lessonPlans->count() ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Recent Activity</h4>
                        <div class="space-y-2">
                            <div class="text-sm text-gray-600">
                                Last login: {{ $teacher->last_login_at ? \Carbon\Carbon::parse($teacher->last_login_at)->diffForHumans() : 'Never' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Profile updated: {{ $teacher->updated_at ? \Carbon\Carbon::parse($teacher->updated_at)->diffForHumans() : 'Never' }}
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
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedTab = document.getElementById(tabName + '-tab');
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
    }
    
    // Add active class to clicked button
    const clickedButton = event.target;
    clickedButton.classList.add('active', 'border-blue-500', 'text-blue-600');
    clickedButton.classList.remove('border-transparent', 'text-gray-500');
}

// Populate classes based on selected branch
function populateClasses(branchId) {
    const classSelect = document.getElementById('class_id');
    classSelect.innerHTML = '<option value="">Loading classes...</option>';
    
    if (!branchId) {
        classSelect.innerHTML = '<option value="">Select Class</option>';
        return;
    }
    
    // Make AJAX request to get classes for the selected branch
    fetch(`/api/branches/${branchId}/classes`)
        .then(response => response.json())
        .then(data => {
            classSelect.innerHTML = '<option value="">Select Class</option>';
            data.forEach(schoolClass => {
                const option = document.createElement('option');
                option.value = schoolClass.id;
                option.textContent = schoolClass.name;
                classSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            classSelect.innerHTML = '<option value="">Error loading classes</option>';
        });
}

// Initialize with first tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('classes');
    
    // Add event listener for branch selection
    const branchSelect = document.getElementById('branch_id');
    if (branchSelect) {
        branchSelect.addEventListener('change', function() {
            populateClasses(this.value);
        });
    }
    
    // Add event listener for class filter
    const classFilter = document.getElementById('class-filter');
    if (classFilter) {
        classFilter.addEventListener('change', function() {
            filterStudentsByClass(this.value);
        });
    }
    
    // Add event listener for student search
    const studentSearch = document.getElementById('student-search');
    if (studentSearch) {
        studentSearch.addEventListener('input', function() {
            searchStudents(this.value);
        });
    }
});

// Filter students by selected class
function filterStudentsByClass(classId) {
    const studentRows = document.querySelectorAll('.student-row');
    
    studentRows.forEach(row => {
        if (!classId || row.dataset.class === classId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Search students by name or email
function searchStudents(searchTerm) {
    const studentRows = document.querySelectorAll('.student-row');
    const searchLower = searchTerm.toLowerCase();
    
    studentRows.forEach(row => {
        const studentName = row.querySelector('td:first-child .text-sm.font-medium').textContent.toLowerCase();
        const studentEmail = row.querySelector('td:first-child .text-sm.text-gray-500').textContent.toLowerCase();
        
        if (studentName.includes(searchLower) || studentEmail.includes(searchLower)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.tab-button.active {
    @apply border-blue-500 text-blue-600;
}

.tab-button:not(.active) {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}
</style>
@endpush

