@extends('layouts.dashboard')

@section('title', 'Academic Calendar')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Academic Calendar</h1>
            @if($currentYear)
                <p class="text-gray-600">{{ $currentYear->name }} - Calendar View</p>
            @else
                <p class="text-gray-600">Select a branch to view the academic calendar</p>
            @endif
        </div>

        <!-- Branch Selection -->
        @if(isset($branches) && $branches->count() > 1)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Branch</h3>
                <form method="GET" action="{{ route('dashboard.calendar') }}" class="flex items-center gap-4">
                    <select name="branch_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ session('current_branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-all duration-200 hover:scale-105">
                        View Calendar
                    </button>
                </form>
            </div>
        @endif

        @if(!$currentYear)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <div class="text-yellow-800">
                    <p class="text-lg font-medium">No Active Academic Year Found</p>
                    <p class="mt-2">Please select a branch that has an active academic year to view the calendar.</p>
                </div>
            </div>
        @else

        <!-- Calendar Legend -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Calendar Legend</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Events</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Holidays</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Exams</span>
                </div>
            </div>
        </div>

        <!-- Monthly Calendar -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900" id="currentMonth">September 2024</h2>
                <div class="flex space-x-2">
                    <button onclick="previousMonth()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-all duration-200 hover:scale-105">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="nextMonth()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-all duration-200 hover:scale-105">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                <!-- Day Headers -->
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Sun</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Mon</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Tue</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Wed</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Thu</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Fri</div>
                <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-700">Sat</div>

                <!-- Calendar Days -->
                <div id="calendarDays" class="col-span-7 grid grid-cols-7 gap-px bg-gray-200">
                    <!-- Calendar days will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Event Details Modal -->
        <div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Event Details</h3>
                        <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer transition-all duration-200 hover:scale-105">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent" class="text-sm text-gray-600">
                        <!-- Event content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
let currentDate = new Date(2024, 8, 1); // Start with September 2024
const calendarEvents = @json($calendarEvents);

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update month display
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay()); // Start from Sunday
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Generate calendar days
    for (let i = 0; i < 42; i++) { // 6 weeks * 7 days
        const currentDay = new Date(startDate);
        currentDay.setDate(startDate.getDate() + i);
        
        const dayElement = document.createElement('div');
        dayElement.className = 'bg-white p-2 min-h-24';
        
        // Check if day is in current month
        const isCurrentMonth = currentDay.getMonth() === month;
        const isToday = currentDay.toDateString() === new Date().toDateString();
        
        if (!isCurrentMonth) {
            dayElement.className += ' bg-gray-50 text-gray-400';
        }
        
        if (isToday) {
            dayElement.className += ' bg-blue-50 border-2 border-blue-200';
        }
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = 'text-sm font-medium mb-1';
        dayNumber.textContent = currentDay.getDate();
        dayElement.appendChild(dayNumber);
        
        // Events for this day
        const dayEvents = getEventsForDate(currentDay);
        dayEvents.forEach(event => {
            const eventElement = createEventElement(event);
            dayElement.appendChild(eventElement);
        });
        
        calendarDays.appendChild(dayElement);
    }
}

function getEventsForDate(date) {
    const dateString = date.toISOString().split('T')[0];
    return calendarEvents.filter(event => {
        const eventStart = new Date(event.start);
        const eventEnd = new Date(event.end);
        const checkDate = new Date(dateString);
        
        return checkDate >= eventStart && checkDate <= eventEnd;
    });
}

function createEventElement(event) {
    const eventDiv = document.createElement('div');
    eventDiv.className = 'text-xs p-1 mb-1 rounded cursor-pointer text-white truncate';
    eventDiv.style.backgroundColor = event.color;
    eventDiv.textContent = event.title;
    eventDiv.onclick = () => showEventDetails(event);
    
    return eventDiv;
}

function showEventDetails(event) {
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = event.title;
    
    let content = '';
    if (event.type === 'event') {
        content = `
            <p class="mb-2"><strong>Type:</strong> ${event.data.event_type}</strong></p>
            <p class="mb-2"><strong>Date:</strong> ${formatDate(event.data.start_date)} - ${formatDate(event.data.end_date)}</p>
            ${event.data.is_all_day ? `<p class="mb-2"><strong>Time:</strong> All Day</p>` : (event.data.start_time && event.data.end_time ? `<p class="mb-2"><strong>Time:</strong> ${formatTime(event.data.start_time)} - ${formatTime(event.data.end_time)}</p>` : '')}
            ${event.data.location ? `<p class="mb-2"><strong>Location:</strong> ${event.data.location}</p>` : ''}
            ${event.data.description ? `<p class="mb-2"><strong>Description:</strong> ${event.data.description}</p>` : ''}
            <p class="mb-2"><strong>Priority:</strong> ${event.data.priority}</p>
        `;
    } else if (event.type === 'holiday') {
        content = `
            <p class="mb-2"><strong>Type:</strong> ${event.data.holiday_type}</strong></p>
            <p class="mb-2"><strong>Date:</strong> ${formatDate(event.data.start_date)} - ${formatDate(event.data.end_date)}</p>
            ${event.data.description ? `<p class="mb-2"><strong>Description:</strong> ${event.data.description}</p>` : ''}
            <p class="mb-2"><strong>Public Holiday:</strong> ${event.data.is_public_holiday ? 'Yes' : 'No'}</p>
        `;
    } else if (event.type === 'exam') {
        content = `
            <p class="mb-2"><strong>Type:</strong> ${event.data.exam_type}</strong></p>
            <p class="mb-2"><strong>Date:</strong> ${formatDate(event.data.exam_date)}</p>
            ${event.data.start_time ? `<p class="mb-2"><strong>Time:</strong> ${event.data.start_time} - ${event.data.end_time}</p>` : ''}
            <p class="mb-2"><strong>Duration:</strong> ${event.data.duration_minutes} minutes</p>
            <p class="mb-2"><strong>Total Marks:</strong> ${event.data.total_marks}</p>
            <p class="mb-2"><strong>Passing Marks:</strong> ${event.data.passing_marks}</p>
            ${event.data.location ? `<p class="mb-2"><strong>Location:</strong> ${event.data.location}</p>` : ''}
            ${event.data.instructions ? `<p class="mb-2"><strong>Instructions:</strong> ${event.data.instructions}</p>` : ''}
        `;
    }
    
    modalContent.innerHTML = content;
    modal.classList.remove('hidden');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function formatTime(timeString) {
    if (!timeString) return '';
    
    // Handle both datetime strings and time-only strings
    let time;
    if (timeString.includes('T')) {
        // Full datetime string (e.g., "2024-09-02T09:00:00.000000Z")
        time = new Date(timeString);
    } else {
        // Time-only string (e.g., "09:00:00")
        time = new Date('1970-01-01T' + timeString);
    }
    
    return time.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
}

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
});

// Close modal when clicking outside
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEventModal();
    }
});
</script>
@endsection

