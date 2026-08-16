{{-- Super Admin Sidebar - Full system access --}}
<div class="mt-4 text-xs uppercase text-gray-500 px-3">System Management</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('branches.index') }}">
        <i class="fas fa-building mr-2"></i>Branches
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('class-management') }}">
        <i class="fas fa-chalkboard-teacher mr-2"></i>Classes
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('admin-user-management') }}">
        <i class="fas fa-user-shield mr-2"></i>Admin Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('admin.teachers.index') }}">
        <i class="fas fa-user-cog mr-2"></i>Teacher Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(147,51,234); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(126,34,206)'" 
       onmouseout="this.style.backgroundColor='rgb(147,51,234)'" 
       href="{{ route('admin.students.index') }}">
        <i class="fas fa-user-graduate mr-2"></i>Student Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 whitespace-nowrap"
       style="background-color: rgb(239,68,68); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,38,38)'" 
       onmouseout="this.style.backgroundColor='rgb(239,68,68)'" 
       href="{{ route('admin.form-teacher-assignments.index') }}">
        <i class="fas fa-chalkboard-teacher mr-2"></i>Form Teacher Management
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
        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 whitespace-nowrap"
           style="background-color: rgb(34,197,94); color: white;" 
           onmouseover="this.style.backgroundColor='rgb(22,163,74)'" 
           onmouseout="this.style.backgroundColor='rgb(34,197,94)'" 
           href="{{ route('academic-calendar.index') }}">
            <i class="fas fa-calendar-alt mr-2"></i>Academy Section
        </a>
        
        <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
           style="background-color: rgb(59,130,246); color: white;" 
           onmouseover="this.style.backgroundColor='rgb(37,99,235)'" 
           onmouseout="this.style.backgroundColor='rgb(59,130,246)'" 
           href="{{ route('subjects.index') }}">
            <i class="fas fa-book mr-2"></i>Subjects
        </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Communication</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('bulk-email.index') }}">
        <i class="fas fa-envelope mr-2"></i>Bulk Email to Parents
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Content Management</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('school-news.admin') }}">
        <i class="fas fa-newspaper mr-2"></i>School News
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('gallery.admin') }}">
        <i class="fas fa-images mr-2"></i>Gallery Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('admin.exam-timetables.index') }}">
        <i class="fas fa-calendar-alt mr-2"></i>Exam Timetables
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('admin.syllabus.index') }}">
        <i class="fas fa-book mr-2"></i>Syllabus Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('admin.elibrary.index') }}">
        <i class="fas fa-book-open mr-2"></i>E-Library Management
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('admin.materials.index') }}">
        <i class="fas fa-file-alt mr-2"></i>Study Materials
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Global Monitoring</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('dashboard', ['global' => 1]) }}">
        <i class="fas fa-chart-line mr-2"></i>Global Dashboard
    </a>
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(236,81,34); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(220,76,24)'" 
       onmouseout="this.style.backgroundColor='rgb(236,81,34)'" 
       href="{{ route('admin.admissions.index') }}">
        <i class="fas fa-user-plus mr-2"></i>All Admissions
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
    
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105 relative whitespace-nowrap"
       style="background-color: rgb(147,51,234); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(126,34,206)'" 
       onmouseout="this.style.backgroundColor='rgb(147,51,234)'" 
       href="{{ route('teacher-attendance.index') }}">
        <i class="fas fa-chalkboard-teacher mr-2"></i>Teachers Attendance
        {{-- <span
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">!</span>
        --}}
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Account</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(16,185,129); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(5,150,105)'" 
       onmouseout="this.style.backgroundColor='rgb(16,185,129)'" 
       href="{{ route('super-admin.profile') }}">
        <i class="fas fa-user mr-2"></i>My Profile
    </a>
</div>

<div class="mt-4 text-xs uppercase text-gray-500 px-3">Settings</div>
<div class="px-3 pt-2 space-y-2">
    <a class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" 
       style="background-color: rgb(37,99,235); color: white;" 
       onmouseover="this.style.backgroundColor='rgb(29,78,216)'" 
       onmouseout="this.style.backgroundColor='rgb(37,99,235)'" 
       href="{{ route('dashboard.select-branch') }}">
        <i class="fas fa-exchange-alt mr-2"></i>Switch Branch
    </a>
</div>

