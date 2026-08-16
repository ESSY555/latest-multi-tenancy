@extends('layouts.dashboard')

@section('title', $student->name . ' - Student Details')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('form-teacher.students') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                            <i class="fas fa-arrow-left mr-1"></i>Back to Students
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $student->name }}</h1>
                    </div>
                    <p class="mt-2 text-gray-600">Student Profile & Activities - {{ $formTeacher->schoolClass->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-user-graduate mr-1"></i>Student
                    </span>
                    @if($student->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Student Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-2xl font-bold text-gray-700">
                            {{ substr($student->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $student->name }}</h2>
                        <p class="text-gray-600">{{ $student->email }}</p>
                        <p class="text-sm text-gray-500">ID: {{ $student->student_id ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $attendance->count() }}</div>
                    <div class="text-sm text-gray-600">Attendance Records</div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $assignments->count() }}</div>
                    <div class="text-sm text-gray-600">Total Assignments</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('form-teacher.remarks.create') }}?student_id={{ $student->id }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-comment-alt mr-2"></i>Add Remark
                </a>
                <a href="{{ route('form-teacher.attendance') }}?student_id={{ $student->id }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-calendar-check mr-2"></i>Mark Attendance
                </a>
                @php
                    $latestResult = $results->first();
                @endphp
                @if($latestResult)
                    <a href="{{ route('result.student-sheet', ['student' => $student->id, 'term' => $latestResult->term_id]) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <i class="fas fa-file-signature mr-2"></i>View/Sign Report Card
                    </a>
                @endif
                <a href="#" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-envelope mr-2"></i>Contact Parent
                </a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button onclick="showTab('attendance')" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-calendar-check mr-2"></i>Attendance
                    </button>
                    <button onclick="showTab('assignments')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-tasks mr-2"></i>Assignments
                    </button>
                    <button onclick="showTab('results')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-chart-bar mr-2"></i>Results
                    </button>
                    <button onclick="showTab('remarks')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-comment-alt mr-2"></i>Remarks
                    </button>
                    <button onclick="showTab('activities')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-history mr-2"></i>Recent Activities
                    </button>
                </nav>
            </div>

            <!-- Attendance Tab -->
            <div id="attendance-tab" class="tab-content p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Attendance Records</h3>
                    <span class="text-sm text-gray-500">{{ $attendance->count() }} records</span>
                </div>
                
                @if($attendance->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attendance->take(10) as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->status === 'present')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Present
                                            </span>
                                        @elseif($record->status === 'absent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Absent
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Late
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $record->notes ?? 'No notes' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No attendance records found for this student.</p>
                    </div>
                @endif
            </div>

            <!-- Assignments Tab -->
            <div id="assignments-tab" class="tab-content p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Assignment Records</h3>
                    <span class="text-sm text-gray-500">{{ $assignments->count() }} assignments</span>
                </div>
                
                @if($assignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignments as $assignment)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $assignment->title }}</h4>
                                    <p class="text-sm text-gray-500">Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'No due date' }}</p>
                                </div>
                                <div class="text-right">
                                    @if($assignment->submissions->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Submitted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Not Submitted
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No assignment records found for this student.</p>
                    </div>
                @endif
            </div>

            <!-- Results Tab -->
            <div id="results-tab" class="tab-content p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Academic Results</h3>
                    <span class="text-sm text-gray-500">{{ $results->count() }} results</span>
                </div>
                
                @if($results->count() > 0)
                    @php
                        $resultsByTerm = $results->groupBy('term_id');
                    @endphp
                    
                    @foreach($resultsByTerm as $termId => $termResults)
                        @php $term = $termResults->first()->academicTerm; @endphp
                        <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div>
                                    <h4 class="font-bold text-gray-800 uppercase tracking-wide">{{ $term->name ?? 'Unknown Term' }}</h4>
                                    <p class="text-xs text-gray-500 uppercase">{{ $term->academicYear->name ?? '' }}</p>
                                </div>
                                <a href="{{ route('result.student-sheet', ['student' => $student->id, 'term' => $termId]) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors uppercase">
                                    <i class="fas fa-file-signature mr-2"></i>View/Sign Full Report Card
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA(30)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam(70)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($termResults as $result)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 uppercase">
                                                {{ $result->subject->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ number_format(($result->ca1 + $result->ca2 + $result->ca3), 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                                {{ number_format($result->exam, 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $result->total < 45 ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ number_format($result->total, 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                    {{ $result->grade ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No academic results found for this student.</p>
                    </div>
                @endif
            </div>

            <!-- Remarks Tab -->
            <div id="remarks-tab" class="tab-content p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Student Remarks</h3>
                    <span class="text-sm text-gray-500">{{ $remarks->count() }} remarks</span>
                </div>
                
                @if($remarks->count() > 0)
                    <div class="space-y-4">
                        @foreach($remarks as $remark)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($remark->type === 'academic') bg-blue-100 text-blue-800
                                            @elseif($remark->type === 'behavioral') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($remark->type) }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $remark->date->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-gray-900">{{ $remark->remark }}</p>
                                    @if($remark->action_taken)
                                        <p class="text-sm text-gray-600 mt-1"><strong>Action:</strong> {{ $remark->action_taken }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comment-alt text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No remarks found for this student.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activities Tab -->
            <div id="activities-tab" class="tab-content p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activities (Last 30 Days)</h3>
                    <span class="text-sm text-gray-500">{{ $recentActivities->count() }} activities</span>
                </div>
                
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($activity['type'] === 'attendance') 
                                                @if($activity['status'] === 'present') bg-green-100 text-green-800
                                                @elseif($activity['status'] === 'absent') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif
                                            @elseif($activity['type'] === 'assignment') bg-blue-100 text-blue-800
                                            @elseif($activity['type'] === 'remark') 
                                                @if($activity['status'] === 'academic') bg-purple-100 text-purple-800
                                                @else bg-orange-100 text-orange-800 @endif
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="fas 
                                                @if($activity['type'] === 'attendance') fa-calendar-check
                                                @elseif($activity['type'] === 'assignment') fa-tasks
                                                @elseif($activity['type'] === 'remark') fa-comment-alt
                                                @else fa-circle @endif mr-1"></i>
                                            {{ ucfirst($activity['type']) }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ \Carbon\Carbon::parse($activity['date'])->format('M d, Y') }}</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">{{ $activity['title'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No recent activities found for this student.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to selected tab button
    event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection

