{{-- Form Teacher Sidebar - Form Teacher-specific access only --}}
<div class="mt-4 text-xs uppercase text-gray-500 px-3">Form Teacher</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.dashboard') }}">
        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('form-teacher.attendance') }}">
        <i class="fas fa-calendar-check mr-2"></i>Daily Attendance
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.report-cards') }}">
        <i class="fas fa-file-signature mr-2"></i>Report Cards
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.students') }}">
        <i class="fas fa-users mr-2"></i>Student Records
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('form-teacher.assignments') }}">
        <i class="fas fa-tasks mr-2"></i>Monitor Assignments
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.remarks') }}">
        <i class="fas fa-comment-alt mr-2"></i>Student Remarks
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('form-teacher.announcements') }}">
        <i class="fas fa-bullhorn mr-2"></i>Announcements
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.reports') }}">
        <i class="fas fa-chart-bar mr-2"></i>Reports
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
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Communication</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('form-teacher.announcements.create') }}">
        <i class="fas fa-plus mr-2"></i>New Announcement
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('form-teacher.remarks.create') }}">
        <i class="fas fa-plus mr-2"></i>Add Student Remark
    </a>
</div>

