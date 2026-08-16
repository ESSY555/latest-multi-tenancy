@extends('layouts.dashboard')

@section('title', 'Daily Attendance')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Daily Attendance</h1>
            <p class="mt-2 text-gray-600">Record attendance for {{ $class->name }}</p>
        </div>

        <!-- Date Selection and Daily Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Daily Attendance Management</h3>
                    <p class="text-sm text-gray-500">Each day starts fresh - previous attendance is saved in the database</p>
                </div>
                @if($selectedDate->isToday())
                    @if($todayAttendanceRecorded)
                        <div class="flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Today's attendance recorded
                        </div>
                    @else
                        <div class="flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Attendance pending for today
                        </div>
                    @endif
                @endif
            </div>
            
            <form method="GET" action="{{ route('form-teacher.attendance') }}" class="space-y-6">
                <!-- Enhanced Search Fields -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-blue-900">Search Attendance Records</h3>
                            <p class="text-sm text-blue-700">Find specific attendance data by date and/or student name</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Date Field -->
                        <div class="bg-white rounded-lg border-2 border-blue-200 p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-center mb-2">
                                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <label for="date" class="text-lg font-semibold text-gray-900">Select Date</label>
                            </div>
                            <input type="date" name="date" id="date" value="{{ $date }}" 
                                   class="block w-full text-lg px-4 py-3 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-blue-50">
                            <p class="mt-2 text-sm text-gray-600">Choose the date to view attendance records</p>
                        </div>
                        
                        <!-- Student Name Field -->
                        <div class="bg-white rounded-lg border-2 border-purple-200 p-4 hover:border-purple-300 transition-colors">
                            <div class="flex items-center mb-2">
                                <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <label for="student_name" class="text-lg font-semibold text-gray-900">Search Student</label>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Optional</span>
                            </div>
                            <input type="text" name="student_name" id="student_name" value="{{ $studentName }}" 
                                   placeholder="Enter student name to filter results..."
                                   class="block w-full text-lg px-4 py-3 border-2 border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-purple-50">
                            <p class="mt-2 text-sm text-gray-600">Leave empty to view all students for the selected date</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center justify-between mt-6 pt-6 border-t border-blue-200">
                        <div class="flex items-center space-x-3">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transform hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search Attendance
                            </button>
                            
                            @if($selectedDate->isToday() && !$studentName)
                                <a href="{{ route('form-teacher.attendance') }}" class="inline-flex items-center px-4 py-2 border-2 border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Today
                                </a>
                            @endif
                        </div>
                        
                        @if($studentName)
                            <a href="{{ route('form-teacher.attendance', ['date' => $date]) }}" class="inline-flex items-center px-4 py-2 border-2 border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Search
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Enhanced Search Instructions -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-green-900">How to Search Attendance</h3>
                            <p class="text-sm text-green-700">Learn how to find the attendance records you need</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg border border-green-200 p-4">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <h4 class="font-semibold text-gray-900">Date Only</h4>
                            </div>
                            <p class="text-sm text-gray-600">Select a date to view <strong>all students'</strong> attendance for that specific day</p>
                        </div>
                        
                        <div class="bg-white rounded-lg border border-green-200 p-4">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-purple-600 font-bold text-sm">2</span>
                                </div>
                                <h4 class="font-semibold text-gray-900">Date + Student Name</h4>
                            </div>
                            <p class="text-sm text-gray-600">Enter both date and student name to find <strong>specific student's</strong> attendance</p>
                        </div>
                        
                        <div class="bg-white rounded-lg border border-green-200 p-4">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-green-600 font-bold text-sm">3</span>
                                </div>
                                <h4 class="font-semibold text-gray-900">Empty Student Field</h4>
                            </div>
                            <p class="text-sm text-gray-600">Leave student name empty to view <strong>all students</strong> for the selected date</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

       
        <!-- Attendance Form -->
        <form method="POST" action="{{ route('form-teacher.attendance.store') }}">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                @if($studentName)
                                    Attendance for {{ $studentName }} on {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                                @else
                                    Attendance for {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                                @endif
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                @if($studentName)
                                    Viewing attendance for specific student
                                @elseif($selectedDate->isToday())
                                    Mark today's attendance - this will be saved to the database
                                @else
                                    View/edit attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                @endif
                            </p>
                            @if($studentName)
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="{{ route('form-teacher.attendance', ['date' => $date]) }}" class="hover:underline">
                                        ← View all students for this date
                                    </a>
                                </p>
                            @endif
                        </div>
                        @if($selectedDate->isToday() && !$studentName)
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Daily Refresh</div>
                                <div class="text-xs text-gray-400">New day, new attendance</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="border-t border-gray-200">
                    @if($studentName && $displayStudents->count() == 0)
                        <div class="px-4 py-8 sm:px-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                            <p class="mt-1 text-sm text-gray-500">No students found matching "{{ $studentName }}" for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                            <div class="mt-4">
                                <a href="{{ route('form-teacher.attendance', ['date' => $date]) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    View all students for this date
                                </a>
                            </div>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @forelse($displayStudents as $enrollment)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($enrollment->student->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $enrollment->student->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $enrollment->student->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    @php
                                        $currentStatus = $attendance->get($enrollment->student->id)->status ?? null;
                                    @endphp
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="attendance[{{ $enrollment->student->id }}][status]" value="present" 
                                               {{ $currentStatus === 'present' ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-900">Present</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="attendance[{{ $enrollment->student->id }}][status]" value="absent" 
                                               {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-900">Absent</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="attendance[{{ $enrollment->student->id }}][status]" value="late" 
                                               {{ $currentStatus === 'late' ? 'checked' : '' }}
                                               class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-900">Late</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="attendance[{{ $enrollment->student->id }}][status]" value="excused" 
                                               {{ $currentStatus === 'excused' ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-900">Excused</span>
                                    </label>
                                    
                                    <input type="hidden" name="attendance[{{ $enrollment->student->id }}][student_id]" value="{{ $enrollment->student->id }}">
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-4 py-8 sm:px-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                            <p class="mt-1 text-sm text-gray-500">No students are currently enrolled in this class.</p>
                        </li>
                        @endforelse
                    </ul>
                    @endif
                </div>

                @if($displayStudents instanceof \Illuminate\Pagination\LengthAwarePaginator && $displayStudents->hasPages())
                <div class="px-4 py-3 bg-white border-t border-gray-200">
                    {{ $displayStudents->links() }}
                </div>
                @endif

                @if($displayStudents->count() > 0)
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            @if($studentName)
                                <span>Viewing {{ $displayStudents->count() }} student(s) matching "{{ $studentName }}"</span>
                            @elseif($selectedDate->isToday())
                                @if($todayAttendanceRecorded)
                                    <span class="text-green-600">✓ Today's attendance already recorded</span>
                                @else
                                    <span class="text-yellow-600">⚠ Please record today's attendance</span>
                                @endif
                            @else
                                <span>Viewing attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            @if($studentName)
                                Update Student Attendance
                            @elseif($selectedDate->isToday())
                                Save Today's Attendance
                            @else
                                Update Attendance
                            @endif
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </form>


         <!-- Recent Attendance History -->
         @if($recentAttendance->count() > 0)
         <div class="bg-white rounded-lg shadow p-6 mb-8 mt-8">
             <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Attendance History</h3>
             <div class="overflow-x-auto">
                 <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                         <tr>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                         </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                         @foreach($recentAttendance->take(5) as $date => $records)
                         <tr>
                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                 {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                 @if(\Carbon\Carbon::parse($date)->isToday())
                                     <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Today</span>
                                 @endif
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                 {{ $records->where('status', 'present')->count() }}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                 {{ $records->where('status', 'absent')->count() }}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                 {{ $records->where('status', 'late')->count() }}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                 {{ $records->where('status', 'excused')->count() }}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 <a href="{{ route('form-teacher.attendance', ['date' => $date]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                             </td>
                         </tr>
                         @endforeach
                     </tbody>
                 </table>
             </div>
         </div>
         @endif
    </div>
</div>
@endsection

