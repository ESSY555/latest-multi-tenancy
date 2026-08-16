@props(['timetables' => null])

<div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Exam Timetables</h2>
        @can('super_admin')
        <a href="{{ route('exam-timetables.create') }}" class="inline-flex w-full items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create New Timetable
        </a>
        @endcan
    </div>

    <!-- Current Exam Period -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900">Current Exam Period</h3>
                <p class="text-blue-700">Mid-Term Examinations - September 10-15, 2025</p>
            </div>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Active</span>
        </div>
    </div>

    <!-- Exam Timetable Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Venue</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Invigilator</th>
                    @can('super_admin')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Sample Exam Schedule -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">10/09/2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Monday</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">9:00 AM – 11:00 AM</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">JSS1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mathematics</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Hall A</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mr. Johnson</td>
                    @can('super_admin')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex space-x-2">
                            <a href="{{ route('exam-timetables.edit', 1) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('exam-timetables.destroy', 1) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">10/09/2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Monday</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">12:00 PM – 2:00 PM</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">JSS2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">English Language</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Hall B</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mrs. Smith</td>
                    @can('super_admin')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex space-x-2">
                            <a href="{{ route('exam-timetables.edit', 2) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('exam-timetables.destroy', 2) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11/09/2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Tuesday</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">9:00 AM – 11:00 AM</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">SS1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Physics</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Lab 1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mr. Adams</td>
                    @can('super_admin')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex space-x-2">
                            <a href="{{ route('exam-timetables.edit', 3) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('exam-timetables.destroy', 3) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11/09/2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Tuesday</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">12:00 PM – 2:00 PM</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">SS2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Chemistry</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Lab 2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mrs. Wilson</td>
                    @can('super_admin')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex space-x-2">
                            <a href="{{ route('exam-timetables.edit', 4) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('exam-timetables.destroy', 4) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">12/09/2025</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Wednesday</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">9:00 AM – 11:00 AM</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">JSS3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Social Studies</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Hall C</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mr. Brown</td>
                    @can('super_admin')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex space-x-2">
                            <a href="{{ route('exam-timetables.edit', 5) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('exam-timetables.destroy', 5) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endcan
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Export Options -->
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-gray-600">
            <span class="font-medium">Total Exams:</span> 5 scheduled
        </div>
        @can('super_admin')
        <div class="grid grid-cols-1 gap-2 sm:flex sm:space-x-3">
            <button class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </button>
            <button class="inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                </svg>
                Export Excel
            </button>
        </div>
        @endcan
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2">Total Exams</h4>
            <p class="text-2xl font-bold text-blue-600">5</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h4 class="font-semibold text-green-900 mb-2">This Week</h4>
            <p class="text-2xl font-bold text-green-600">3</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h4 class="font-semibold text-yellow-900 mb-2">Next Week</h4>
            <p class="text-2xl font-bold text-yellow-600">2</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h4 class="font-semibold text-purple-900 mb-2">Venues Used</h4>
            <p class="text-2xl font-bold text-purple-600">4</p>
        </div>
    </div>
</div>

