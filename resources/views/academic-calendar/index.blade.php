@extends('layouts.dashboard')

@section('title', 'Academy Section')

@section('dashboard')
    <div class="w-full max-w-full min-w-0 mx-auto px-0 sm:px-2 py-6 sm:py-8">
        <!-- <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-start mb-8">
                        <div class="min-w-0">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Academy Section</h1>
                            <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage academic years, terms, events, and holidays</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 shrink-0 w-full sm:w-auto">
                            <a href="{{ route('academic-calendar.calendar') }}"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Calendar View
                            </a>
                            <a href="{{ route('academic-calendar.years.create') }}"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Academic Section
                            </a>
                        </div>
                    </div> -->


        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

            <!-- LEFT TEXT -->
            <div class="max-w-xl">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Academy Section
                </h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">
                    Manage academic years, terms, events, and holidays
                </p>
            </div>

            <!-- RIGHT BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <a href="{{ route('academic-calendar.calendar') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Calendar View
                </a>

                <a href="{{ route('academic-calendar.years.create') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Academic Section
                </a>

            </div>
        </div>






        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Current Academic Year Overview -->
        @if($currentYear)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-4">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 min-w-0 break-words pr-2">Current Academic
                        Section: {{ $currentYear->name }}</h2>
                    <span
                        class="inline-flex items-center self-start sm:self-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0">
                        Active
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $currentYear->start_date->format('M Y') }}</div>
                        <div class="text-sm text-gray-500">Start Date</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $currentYear->end_date->format('M Y') }}</div>
                        <div class="text-sm text-gray-500">End Date</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $currentYear->getDurationInDays() }}</div>
                        <div class="text-sm text-gray-500">Total Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $currentYear->getProgressPercentage() }}%</div>
                        <div class="text-sm text-gray-500">Progress</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $currentYear->getProgressPercentage() }}%">
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('academic-calendar.terms.create', $currentYear->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Term
                    </a>
                    <a href="{{ route('academic-calendar.events.create', $currentYear->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Event
                    </a>
                    <a href="{{ route('academic-calendar.holidays.create', $currentYear->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Holiday
                    </a>
                    <a href="{{ route('academic-calendar.exams.create', $currentYear->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Exam
                    </a>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">No Active Academic Year</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You need to create and activate an academic year to start managing the calendar.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('academic-calendar.years.create') }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Create Academic Section
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Upcoming Events and Holidays -->
        @if($currentYear && ($upcomingEvents->count() > 0 || $upcomingHolidays->count() > 0))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Upcoming Events -->
                @if($upcomingEvents->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Events</h3>
                        <div class="space-y-3">
                            @foreach($upcomingEvents as $event)
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                        <div class="flex-shrink-0 w-3 h-3 rounded-full mt-1.5 sm:mt-0"
                                            style="background-color: {{ $event->color ?: '#6b7280' }}"></div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 break-words">{{ $event->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $event->start_date->format('M d, Y') }} -
                                                {{ $event->end_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center self-start sm:self-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upcoming Holidays -->
                @if($upcomingHolidays->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Holidays</h3>
                        <div class="space-y-3">
                            @foreach($upcomingHolidays as $holiday)
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                        <div class="flex-shrink-0 w-3 h-3 rounded-full mt-1.5 sm:mt-0"
                                            style="background-color: {{ $holiday->color ?: '#6b7280' }}"></div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 break-words">{{ $holiday->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $holiday->start_date->format('M d, Y') }} -
                                                {{ $holiday->end_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center self-start sm:self-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 shrink-0">
                                        {{ ucfirst($holiday->holiday_type) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Academic Years List -->
        <div class="bg-white rounded-lg shadow-md min-w-0">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Academic Years</h3>
            </div>
            <div class="overflow-x-auto touch-pan-x">
                <table class="min-w-[640px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Year
                            </th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration</th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terms
                            </th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semesters</th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Events</th>
                            <th
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($academicYears as $year)
                            <tr>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 align-top max-w-[10rem] sm:max-w-none">
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-gray-900 break-words">{{ $year->name }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500 break-words">
                                            {{ $year->description ?: 'No description' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <span class="hidden md:inline">{{ $year->start_date->format('M d, Y') }} -
                                        {{ $year->end_date->format('M d, Y') }}</span>
                                    <span class="md:hidden">{{ $year->start_date->format('M d, y') }} –
                                        {{ $year->end_date->format('M d, y') }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @if($year->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $year->terms->count() }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $year->semesters->count() }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $year->events->count() }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap gap-x-2 gap-y-1">
                                        <a href="{{ route('academic-calendar.years.edit', $year->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @if(!$year->is_active)
                                            <form action="{{ route('academic-calendar.years.update', $year->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_active" value="1">
                                                <button type="submit" class="text-green-600 hover:text-green-900">Activate</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No academic years found.
                                    <a href="{{ route('academic-calendar.years.create') }}"
                                        class="text-indigo-600 hover:text-indigo-900">Create your first academic year</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
