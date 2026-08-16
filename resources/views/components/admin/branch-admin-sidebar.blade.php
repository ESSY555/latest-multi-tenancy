{{-- Branch Admin Sidebar - Branch-specific access only --}}
<div class="mt-4 text-xs uppercase text-gray-500 px-3">Branch Management</div>
<div class="px-3 pt-2 space-y-2">
    {{-- <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('admin-user-management') }}">
        <i class="fas fa-users mr-2"></i>User Management
    </a> --}}
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('admin-manage-teachers') }}">
        <i class="fas fa-chalkboard-teacher mr-2"></i>Manage Teachers
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('admin.teachers.index') }}">
        <i class="fas fa-user-cog mr-2"></i>Teacher Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(168,85,247); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(147,51,234)'" 
       onmouseout="this.style.backgroundColor='rgb(168,85,247)'" 
       href="{{ route('admin.classes.index') }}">
        <i class="fas fa-graduation-cap mr-2"></i>Manage Classes
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('admin.form-teacher-assignments.index') }}">
        <i class="fas fa-user-tie mr-2"></i>Manage Form Teachers
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('admin.students.index') }}">
        <i class="fas fa-user-graduate mr-2"></i>Manage Students
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('admin.admissions.index') }}">
        <i class="fas fa-user-plus mr-2"></i>Admissions
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Academic Oversight</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('subjects.index') }}">
        <i class="fas fa-book mr-2"></i>Subjects
    </a>
    
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
       href="{{ route('attendance.index') }}">
        <i class="fas fa-calendar-check mr-2"></i>Student Attendance
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 relative" 
       style="background-color: rgb(147,51,234); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(126,34,206)'" 
       onmouseout="this.style.backgroundColor='rgb(147,51,234)'" 
       href="{{ route('teacher-attendance.index') }}">
        <i class="fas fa-chalkboard-teacher mr-2"></i>Manage Teacher Attendance
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">!</span>
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(168,85,247); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(147,51,234)'" 
       onmouseout="this.style.backgroundColor='rgb(168,85,247)'" 
       href="{{ route('result.index') }}">
        <i class="fas fa-chart-line mr-2"></i>Result Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(79,70,229); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(67,56,202)'" 
       onmouseout="this.style.backgroundColor='rgb(79,70,229)'" 
       href="{{ route('student.results.annual.index') }}">
        <i class="fas fa-file-alt mr-2"></i>Summary Report
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(14,116,144); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(8,145,178)'" 
       onmouseout="this.style.backgroundColor='rgb(14,116,144)'" 
       href="{{ route('result.mock-index') }}">
        <i class="fas fa-vial mr-2"></i>Mock Results
    </a>

    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(15,118,110); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(13,148,136)'" 
       onmouseout="this.style.backgroundColor='rgb(15,118,110)'" 
       href="{{ route('result.mock-exams') }}">
        <i class="fas fa-flask mr-2"></i>Mock Exams
    </a>

    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105"
       style="background-color: rgb(6,182,212); color: white;"
       onmouseover="this.style.backgroundColor='rgb(8,145,178)'"
       onmouseout="this.style.backgroundColor='rgb(6,182,212)'"
       href="{{ route('exam-timetables.view') }}">
        <i class="fas fa-calendar-alt mr-2"></i>Exam Timetables
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 relative" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('lesson-plans.index') }}">
        <i class="fas fa-clipboard-list mr-2"></i>Lesson Plans
        @if(isset($pendingLessonPlans) && $pendingLessonPlans > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $pendingLessonPlans }}</span>
        @endif
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 relative" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('teacher-reports.index') }}">
        <i class="fas fa-file-alt mr-2"></i>Manage Reports
        @if(isset($pendingTeacherReports) && $pendingTeacherReports > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $pendingTeacherReports }}</span>
        @endif
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Resources & Export</div>
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

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Calendar Management</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('academic-calendar.index') }}">
        <i class="fas fa-calendar mr-2"></i>Academic Calendar
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('dashboard.calendar') }}">
        <i class="fas fa-eye mr-2"></i>View Calendar
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Account</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('admin.profile') }}">
        <i class="fas fa-user mr-2"></i>My Profile
    </a>
</div>

{{-- Branch Admin cannot change branches - they are assigned to one specific branch --}}

