{{-- Teacher Sidebar - Teacher-specific access only --}}
<div class="mt-4 text-xs uppercase text-gray-500 px-3">Teaching</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('assignments.index') }}">
        <i class="fas fa-tasks mr-2"></i>Assignments
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('lesson-plans.index') }}">
        <i class="fas fa-clipboard-list mr-2"></i>Lesson Plans
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('attendance.index') }}">
        <i class="fas fa-calendar-check mr-2"></i>Student Attendance
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('teacher-attendance.daily-view') }}">
        <i class="fas fa-eye mr-2"></i>View Daily Attendance
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(147,51,234); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(126,34,206)'" 
       onmouseout="this.style.backgroundColor='rgb(147,51,234)'" 
       href="{{ route('teacher-attendance.teacher-view') }}">
        <i class="fas fa-user-clock mr-2"></i>My Attendance
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(168,85,247); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(147,51,234)'" 
       onmouseout="this.style.backgroundColor='rgb(168,85,247)'" 
       href="{{ route('result.index') }}">
        <i class="fas fa-chart-line mr-2"></i>Result Management
    </a>

    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(14,116,144); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(8,145,178)'" 
       onmouseout="this.style.backgroundColor='rgb(14,116,144)'" 
       href="{{ route('result.mock-index') }}">
        <i class="fas fa-vial mr-2"></i>Mock Results
    </a>

    @if(auth()->user()->hasRole('form_teacher'))
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(79, 70, 229); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(67, 56, 202)'" 
       onmouseout="this.style.backgroundColor='rgb(79, 70, 229)'" 
       href="{{ route('form-teacher.report-cards') }}">
        <i class="fas fa-file-invoice mr-2"></i>Termly Report Cards
    </a>

    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(168,85,247); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(147,51,234)'" 
       onmouseout="this.style.backgroundColor='rgb(168,85,247)'" 
       href="{{ route('student.results.annual.index') }}">
        <i class="fas fa-file-alt mr-2"></i>Summary Report
    </a>
    @endif
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('teacher-reports.index') }}">
        <i class="fas fa-file-alt mr-2"></i>Teacher Reports
    </a>

    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
       style="background-color: rgb(6,182,212); color: white;"
       onmouseover="this.style.backgroundColor='rgb(8,145,178)'"
       onmouseout="this.style.backgroundColor='rgb(6,182,212)'"
       href="{{ route('exam-timetables.view') }}">
        <i class="fas fa-calendar-alt mr-2"></i>Exam Timetables
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Resources</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('resources.index') }}">
        <i class="fas fa-folder-open mr-2"></i>View Resources
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('syllabus.export.pdf') }}">
        <i class="fas fa-file-pdf mr-2"></i>Export Syllabus PDF
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('syllabus.export.excel') }}">
        <i class="fas fa-file-excel mr-2"></i>Export Syllabus Excel
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Account</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('teacher.profile') }}">
        <i class="fas fa-user mr-2"></i>My Profile
    </a>
</div>


