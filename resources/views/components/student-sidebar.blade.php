<div>
    <div class="mt-2 text-xs uppercase text-gray-500 px-3">Student</div>
    <div class="px-3 pt-2 space-y-2">
        <a href="{{ route('student.dashboard') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(37,99,235); color: white;" onmouseover="this.style.backgroundColor='rgb(29,78,216)'" onmouseout="this.style.backgroundColor='rgb(37,99,235)'">Overview</a>
        <a href="{{ route('student.grades') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(236,81,34); color: white;" onmouseover="this.style.backgroundColor='rgb(220,76,24)'" onmouseout="this.style.backgroundColor='rgb(236,81,34)'">Grades</a>
        <a href="{{ route('student.results') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(34,197,94); color: white;" onmouseover="this.style.backgroundColor='rgb(22,163,74)'" onmouseout="this.style.backgroundColor='rgb(34,197,94)'"><i class="fas fa-file-alt mr-2"></i>My Results</a>
        <a href="{{ route('student.results.annual.index') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(168,85,247); color: white;" onmouseover="this.style.backgroundColor='rgb(147,51,234)'" onmouseout="this.style.backgroundColor='rgb(168,85,247)'"><i class="fas fa-chart-line mr-2"></i>Annual Summary</a>
        <a href="{{ route('result.mock-index') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(14,116,144); color: white;" onmouseover="this.style.backgroundColor='rgb(8,145,178)'" onmouseout="this.style.backgroundColor='rgb(14,116,144)'"><i class="fas fa-vial mr-2"></i>Mock Results</a>
        <a href="{{ route('student.attendance') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(37,99,235); color: white;" onmouseover="this.style.backgroundColor='rgb(29,78,216)'" onmouseout="this.style.backgroundColor='rgb(37,99,235)'">Attendance</a>
        <a href="{{ route('student.assignments') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(236,81,34); color: white;" onmouseover="this.style.backgroundColor='rgb(220,76,24)'" onmouseout="this.style.backgroundColor='rgb(236,81,34)'">Manage Assignments</a>
        <a href="{{ route('student.announcements') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(147,51,234); color: white;" onmouseover="this.style.backgroundColor='rgb(126,34,206)'" onmouseout="this.style.backgroundColor='rgb(147,51,234)'">Class Announcements</a>
        <a href="{{ route('exam-timetables.view') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(6,182,212); color: white;" onmouseover="this.style.backgroundColor='rgb(8,145,178)'" onmouseout="this.style.backgroundColor='rgb(6,182,212)'"><i class="fas fa-calendar-alt mr-2"></i>Exam Timetables</a>
    </div>

    <div class="mt-4 text-xs uppercase text-gray-500 px-3">Account</div>
    <div class="px-3 pt-2 space-y-2">
        <a href="{{ route('student.profile') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(16,185,129); color: white;" onmouseover="this.style.backgroundColor='rgb(5,150,105)'" onmouseout="this.style.backgroundColor='rgb(16,185,129)'">My Profile</a>
    </div>

    <div class="mt-4 text-xs uppercase text-gray-500 px-3">Resources & Export</div>
    <div class="px-3 pt-2 space-y-2">
        <a href="{{ route('resources.index') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(37,99,235); color: white;" onmouseover="this.style.backgroundColor='rgb(29,78,216)'" onmouseout="this.style.backgroundColor='rgb(37,99,235)'">View Resources</a>
        <a href="{{ route('syllabus.export.pdf') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(236,81,34); color: white;" onmouseover="this.style.backgroundColor='rgb(220,76,24)'" onmouseout="this.style.backgroundColor='rgb(236,81,34)'">Export Syllabus PDF</a>
        <a href="{{ route('syllabus.export.excel') }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(37,99,235); color: white;" onmouseover="this.style.backgroundColor='rgb(29,78,216)'" onmouseout="this.style.backgroundColor='rgb(37,99,235)'">Export Syllabus Excel</a>
    </div>

    <div class="mt-4 text-xs uppercase text-gray-500 px-3">Quick Actions</div>
    @php
        $calendarUrl = \Illuminate\Support\Facades\Route::has('student.calendar')
            ? route('student.calendar')
            : route('academic-calendar.calendar');
    @endphp
    <div class="px-3 pt-2 space-y-2">
        <a href="{{ $calendarUrl }}" class="block w-full px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200 hover:scale-105" style="background-color: rgb(37,99,235); color: white;" onmouseover="this.style.backgroundColor='rgb(29,78,216)'" onmouseout="this.style.backgroundColor='rgb(37,99,235)'">View Academic Calendar</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md cursor-pointer transition-all duration-200 hover:scale-105 text-sm font-medium" style="background-color: rgb(236,81,34); color: white;" onmouseover="this.style.backgroundColor='rgb(220,76,24)'" onmouseout="this.style.backgroundColor='rgb(236,81,34)'">Logout</button>
        </form>
    </div>
</div>



