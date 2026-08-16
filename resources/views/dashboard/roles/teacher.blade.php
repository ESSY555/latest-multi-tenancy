@extends('layouts.dashboard')

@section('title', isset($formTeacher) && $formTeacher ? 'Form Teacher Dashboard' : 'Teacher Dashboard')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        @if(isset($formTeacher) && $formTeacher)
                            Form Teacher Dashboard
                        @else
                            Teacher Dashboard
                        @endif
                    </h1>
                    <p class="mt-2 text-gray-600">
                        @if(isset($formTeacher) && $formTeacher)
                            Your classes, assignments, attendance and form teacher responsibilities for {{ $branch->name }}
                        @else
                            Your classes, assignments and attendance for {{ $branch->name }}
                        @endif
                    </p>
                </div>
                @if(isset($formTeacher) && $formTeacher)
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg px-4 sm:px-6 py-4 text-white">
                    <div class="flex items-center">
                        <i class="fas fa-star mr-3 text-2xl"></i>
                        <div>
                            <h2 class="text-lg font-bold">Form Teacher</h2>
                            <p class="text-sm opacity-90">{{ $formTeacher->schoolClass->name }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('form-teacher.dashboard') }}" class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-sm rounded-md transition-all duration-200">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            Form Teacher Dashboard
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Teacher Info Banner -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <div class="flex items-center mt-1">
                        <p class="text-emerald-100">
                            @if(isset($formTeacher) && $formTeacher)
                                Form Teacher & Teacher
                            @else
                                Teacher
                            @endif
                        </p>
                        @if(isset($formTeacher) && $formTeacher)
                            <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-500 text-white">
                                <i class="fas fa-star mr-1"></i>Form Teacher
                            </span>
                        @endif
                    </div>
                    <p class="text-emerald-100">{{ $branch->name }}</p>
                    @if(isset($formTeacher) && $formTeacher)
                        <p class="text-emerald-100 text-sm mt-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Form Teacher of {{ $formTeacher->schoolClass->name }}
                        </p>
                    @endif
                </div>
                <div class="text-left sm:text-right text-white">
                    <div class="text-4xl font-bold">{{ $classes->count() }}</div>
                    <div class="text-emerald-100">Active Classes</div>
                    @if(isset($formTeacher) && $formTeacher)
                        <div class="mt-2">
                            <div class="text-2xl font-bold">{{ $formTeacher->schoolClass->enrollments->count() ?? 0 }}</div>
                            <div class="text-emerald-100 text-sm">Form Class Students</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Classes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $classes->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $classes->sum('enrollments_count') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Attendance Records</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $attendanceCount }}</p>
                    </div>
                </div>
            </div>
        </div>


 <!-- Form Teacher Section (if assigned as form teacher) -->
 @if(isset($formTeacher) && $formTeacher)
 <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-8">
     <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
         <h3 class="text-xl font-bold text-white">Form Teacher Dashboard</h3>
         <a href="{{ route('form-teacher.dashboard') }}" class="inline-flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-md transition-all duration-200">
             <i class="fas fa-external-link-alt mr-2"></i>View Full Dashboard
         </a>
     </div>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white">
         <div class="text-center">
             <div class="text-3xl font-bold">{{ $formTeacher->schoolClass->enrollments->count() ?? 0 }}</div>
             <div class="text-sm opacity-90">Students in {{ $formTeacher->schoolClass->name }}</div>
         </div>
         <div class="text-center">
             <div class="text-3xl font-bold">{{ $formTeacher->schoolClass->name }}</div>
             <div class="text-sm opacity-90">Assigned Class</div>
         </div>
         <div class="text-center">
             <div class="text-3xl font-bold">{{ $formTeacher->start_date ? $formTeacher->start_date->format('M d') : 'N/A' }}</div>
             <div class="text-sm opacity-90">Assignment Start</div>
         </div>
     </div>
     <div class="mt-4 flex flex-wrap gap-2">
         <a href="{{ route('form-teacher.students') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
             <i class="fas fa-users mr-1"></i>Student Records
         </a>
         <a href="{{ route('form-teacher.attendance') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
             <i class="fas fa-calendar-check mr-1"></i>Daily Attendance
         </a>
         <a href="{{ route('form-teacher.remarks') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
             <i class="fas fa-comment-alt mr-1"></i>Student Remarks
         </a>
         <a href="{{ route('form-teacher.announcements') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
             <i class="fas fa-bullhorn mr-1"></i>Announcements
         </a>
     </div>
 </div>
 @endif

        <!-- Charts Section -->
        @if(isset($charts) && count($charts) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Analytics & Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($charts as $chart)
                    <x-dashboard-chart :chart="$chart" />
                @endforeach
            </div>
        </div>
        @endif

       
        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Your Classes -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Classes</h3>
                        <div class="space-y-3">
                            @foreach($classes as $c)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $c->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $c->grade_level }} • {{ $c->academic_year }}</p>
                                    @if(isset($formTeacher) && $formTeacher && $formTeacher->school_class_id == $c->id)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-star mr-1"></i>Form Class
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $c->enrollments_count }} students
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Assignments -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Assignments</h3>
                        <div class="space-y-3">
                            @forelse($myAssignments as $a)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $a->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $a->schoolClass->name }}</p>
                                    <p class="text-xs text-gray-400">Due: {{ $a->due_date ? $a->due_date->format('M d, Y') : 'No due date' }}</p>
                                </div>
                                <div class="text-right">
                                    @if($a->due_date && $a->due_date->isPast())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Overdue
                                        </span>
                                    @elseif($a->due_date && $a->due_date->diffInDays(now()) <= 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Due Soon
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No assignments created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



