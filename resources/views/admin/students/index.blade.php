                                    @extends('layouts.dashboard')

@section('title', 'Students Management')

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Students Management</h1>
                        <p class="text-gray-600 mt-2">Manage students for your branch</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.students.export') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Students
                        </a>
                        <a href="{{ route('admin.students.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium w-auto">
                            Add New Student
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form action="{{ route('admin.students.index') }}" method="GET" class="space-y-4">
                    <!-- Search Input -->
                    <div class="w-full">
                        <label for="search" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search Students</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, email, phone, or admission number..." 
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-4">
                        <!-- Branch Filter (Super Admin Only) -->
                        @if(auth()->user()->is_super_admin)
                        <div class="w-full sm:flex-1 sm:min-w-[12rem]">
                            <label for="branch_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Branch</label>
                            <select name="branch_id" 
                                    id="branch_id" 
                                    class="block w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white">
                                <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Class Filter -->
                    <div class="w-full sm:flex-1 sm:min-w-[12rem]">
                        <label for="class_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Class / Grade</label>
                        <select name="class_id" 
                                id="class_id" 
                                class="block w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 w-full sm:w-auto sm:shrink-0">
                        <button type="submit"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'branch_id', 'class_id']))
                            <a href="{{ route('admin.students.index') }}"
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Clear
                            </a>
                        @endif
                    </div>
                    </div>
                </form>
            </div>

            <!-- Students Table -->
            <div id="students-table-container" class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">All Students</h3>
                </div>

                <!-- Mobile Cards -->
                <div class="block md:hidden divide-y divide-gray-200">
                    @forelse($students as $student)
                        <div class="p-4 space-y-3 mobile-student-card"
                             data-name="{{ strtolower($student->name) }}"
                             data-email="{{ strtolower($student->email) }}"
                             data-phone="{{ strtolower($student->phone) }}"
                             data-admission="{{ strtolower($student->studentProfile->admission_number ?? '') }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($student->profile_photo)
                                            <img src="{{ asset('uploads/profile-photos/' . $student->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.523 18.246 19 16.5 19c-1.746 0-3.332-.477-4.5-1.253"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ $student->email }}</div>
                                        @if($student->admission_number)
                                            <div class="text-xs text-blue-600 font-medium font-mono mt-0.5">Adm No: {{ $student->admission_number }}</div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.students.show', ['student' => $student->id]) }}" class="text-xs text-blue-600 hover:text-blue-900 whitespace-nowrap">View</a>
                            </div>

                            <div class="text-sm text-gray-700 space-y-1">
                                <div><span class="font-medium">Phone:</span> {{ $student->phone ?: 'No phone' }}</div>
                                <div><span class="font-medium">Address:</span> {{ $student->address ? Str::limit($student->address, 40) : 'No address' }}</div>
                                @if(auth()->user()->is_super_admin)
                                    <div><span class="font-medium">Branch:</span> {{ $student->branch_name }}</div>
                                @endif
                                <div><span class="font-medium">Class:</span> {{ $student->grade_level ?: 'Not assigned' }}</div>
                                <div><span class="font-medium">Parent:</span> {{ $student->parent_name ?? 'Not specified' }}</div>
                                <div><span class="font-medium">Contact:</span> {{ $student->parent_contact ?? 'No contact' }}</div>
                            </div>

                            <div class="flex items-center gap-4 pt-1">
                                <a href="{{ route('admin.students.edit', ['student' => $student->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</a>
                                <button onclick="deleteStudent({{ $student->id }}, '{{ $student->name }}')" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            {{ request('search') ? 'User does not exist.' : 'No students found.' }}
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                @if(auth()->user()->is_super_admin)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class / Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 cursor-pointer student-row" 
                                    data-name="{{ strtolower($student->name) }}"
                                    data-email="{{ strtolower($student->email) }}"
                                    data-phone="{{ strtolower($student->phone) }}"
                                    data-admission="{{ strtolower($student->studentProfile->admission_number ?? '') }}"
                                    onclick="window.location.href='{{ route('admin.students.show', ['student' => $student->id]) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center overflow-hidden">
                                                @if($student->profile_photo)
                                                    <img src="{{ asset('uploads/profile-photos/' . $student->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.523 18.246 19 16.5 19c-1.746 0-3.332-.477-4.5-1.253"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                @if($student->admission_number)
                                                    <div class="text-xs text-blue-600 font-medium font-mono mt-0.5">Adm No: {{ $student->admission_number }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $student->phone ?: 'No phone' }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->address ? Str::limit($student->address, 30) : 'No address' }}</div>
                                        </div>
                                    </td>
                                    @if(auth()->user()->is_super_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $student->branch_name }}</span>
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $student->grade_level ?: 'Not assigned' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div>{{ $student->parent_name ?? 'Not specified' }}</div>
                                            <div class="text-gray-500">{{ $student->parent_contact ?? 'No contact' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.students.show', ['student' => $student->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                            <a href="{{ route('admin.students.edit', ['student' => $student->id]) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            <button onclick="deleteStudent({{ $student->id }}, '{{ $student->name }}')" 
                                                    class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    {{ request('search') ? 'User does not exist.' : 'No students found.' }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($students->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Student</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete <span id="studentName" class="font-semibold"></span>? 
                        This action cannot be undone and will remove all associated data.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <input type="hidden" id="studentIdToDelete">
                    <div class="flex justify-center space-x-3">
                        <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const classFilter = document.getElementById('class_id');
        const branchFilter = document.getElementById('branch_id');

        let searchTimeout;
        function filterStudents() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const form = document.querySelector('form');
                const url = new URL(form.action || window.location.href);

                const searchVal = searchInput ? searchInput.value : '';
                if (searchVal) {
                    url.searchParams.set('search', searchVal);
                } else {
                    url.searchParams.delete('search');
                }

                if (branchFilter && branchFilter.value) {
                    url.searchParams.set('branch_id', branchFilter.value);
                } else {
                    url.searchParams.delete('branch_id');
                }

                if (classFilter && classFilter.value) {
                    url.searchParams.set('class_id', classFilter.value);
                } else {
                    url.searchParams.delete('class_id');
                }

                // Fetch the updated table HTML from the backend
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableContainer = doc.getElementById('students-table-container');
                    const currentTableContainer = document.getElementById('students-table-container');

                    if (newTableContainer && currentTableContainer) {
                        currentTableContainer.innerHTML = newTableContainer.innerHTML;

                        // Update URL without reloading page to maintain state
                        window.history.pushState({}, '', url);
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
            }, 400); // 400ms debounce
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterStudents);

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent form submission
                }
            });
        }
    });

    function deleteStudent(studentId, studentName) {
        event.stopPropagation(); // Prevent row click

        document.getElementById('studentIdToDelete').value = studentId;
        document.getElementById('studentName').textContent = studentName;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Close modal when clicking cancel
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
    });

    // Handle delete confirmation
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const studentId = document.getElementById('studentIdToDelete').value;

        fetch(`/admin/students/${studentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Error deleting student');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting student');
        });
    });

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Submit form when branch selection changes
    document.getElementById('branch_id')?.addEventListener('change', function() {
        const classSelect = document.getElementById('class_id');
        if (classSelect) {
            classSelect.value = '';
        }
        this.form.submit();
    });
    </script>
    @endpush

@endsection

