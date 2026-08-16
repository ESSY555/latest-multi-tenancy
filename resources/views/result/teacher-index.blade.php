@extends('layouts.dashboard')

@section('title', 'My Subject Results')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Subject Results</h1>
                    <p class="text-gray-600 mt-2">Enter and manage results for your assigned subjects</p>
                    @if($currentAcademicYear)
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>Current Academic Year: <strong>{{ $currentAcademicYear->name }}</strong>
                        </p>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('result.create') }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                        <i class="fas fa-plus mr-2"></i>Add Result
                    </a>
                </div>
            </div>
        </div>

        <!-- Teacher Info Card -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">Subject Teacher</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Your Role:</strong> Enter CA and Exam scores for your assigned subjects. 
                        The system will automatically calculate totals, grades, and class statistics.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Results</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->where('is_approved', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->where('is_approved', false)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Subjects</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->pluck('subject.name')->unique()->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select name="class_id" id="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select name="subject_id" id="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="term_id" class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                        <select name="term_id" id="term_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Terms</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }} - {{ $term->academicYear->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('result.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">My Results ({{ $results->total() }} total)</h3>
            </div>

            @if($results->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scores</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $result)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-green-800">
                                                        {{ substr($result->student->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $result->student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $result->student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->schoolClass->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->academicTerm->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-xs">
                                            <div>CA1: {{ $result->ca1 }}</div>
                                            <div>CA2: {{ $result->ca2 }}</div>
                                            <div>CA3: {{ $result->ca3 }}</div>
                                            <div>Exam: {{ $result->exam }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $result->total }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($result->grade === 'A') bg-green-100 text-green-800
                                            @elseif($result->grade === 'B') bg-blue-100 text-blue-800
                                            @elseif($result->grade === 'C') bg-yellow-100 text-yellow-800
                                            @elseif($result->grade === 'D') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $result->grade }} - {{ $result->remark }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->position ? $result->position . ((($result->position % 100) >= 11 && ($result->position % 100) <= 13) ? 'th' : (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$result->position % 10])) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($result->is_approved)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('result.show', $result) }}" 
                                               class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                                                <a href="{{ route('result.edit', $result) }}" 
                                                   class="text-green-600 hover:text-green-900 cursor-pointer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $results->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
                        <p class="text-gray-500">You haven't entered any results yet.</p>
                        <a href="{{ route('result.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                            <i class="fas fa-plus mr-2"></i>Add First Result
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Teacher Instructions -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Teacher Instructions</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Enter Scores:</strong> Add CA1, CA2, CA3 (0-10 each) and Exam (0-70) scores</li>
                            <li><strong>Automatic Calculation:</strong> System calculates total, grade, and remark automatically</li>
                            <li><strong>Class Statistics:</strong> System calculates highest, lowest, average, and positions</li>
                            <li><strong>Approval Process:</strong> Results need admin approval before students can view them</li>
                            <li><strong>Edit Results:</strong> You can edit results until they are approved by admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

