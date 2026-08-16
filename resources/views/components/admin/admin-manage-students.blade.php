@props(['students' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Manage Students</h2>
        <button id="add-student-btn"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Student
        </button>
    </div>

    <!-- Student Creation Form (Hidden by default) -->
    <div id="create-student-form" class="mb-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden"
        style="position: relative; z-index: 10;">
        <h3 class="font-bold text-lg mb-4 text-blue-800">Create New Student</h3>
        <form id="student-form" class="space-y-4">
            <!-- Profile Photo Upload -->
            <div class="flex justify-center mb-6">
                <div class="relative group">
                    <div id="avatar-container-create" onclick="triggerPhotoUpload('create')" 
                         class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-lg flex items-center justify-center overflow-hidden cursor-pointer relative border-4 border-white">
                        <span class="text-3xl font-bold text-white" id="create-student-avatar-placeholder">?</span>
                        <img id="create-student-photo-preview" src="" class="hidden w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 mt-2 font-medium text-center">Click to upload photo</p>
                    <input type="file" id="create-student-photo-input" name="profile_photo" class="hidden" accept="image/*" onchange="previewPhoto(event, 'create')">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Full Name</label>
                    <input type="text" id="student-name"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Email</label>
                    <input type="email" id="student-email"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter email" required>
                </div>
            </div>
            @if(auth()->user()->is_super_admin)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Branch *</label>
                        <select id="student-branch"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                            onchange="loadClassesForBranch()" required>
                            <option value="">Select Branch</option>
                            @foreach(\App\Models\Branch::orderBy('name')->get() as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <!-- Empty div for grid alignment -->
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Phone Number</label>
                    <input type="tel" id="student-phone"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter phone number">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Date of Birth</label>
                    <input type="date" id="student-dob"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Address</label>
                    <input type="text" id="student-address"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter address">
                </div>
                <div>
                    <!-- Empty div for grid alignment -->
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Guardian Name</label>
                    <input type="text" id="student-guardian-name"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter guardian name">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Guardian Phone</label>
                    <input type="tel" id="student-guardian-phone"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter guardian phone">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Assign Class</label>
                    <select id="student-class"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        onchange="loadSubjectsForClass()">
                        <option value="">Select class</option>
                        @if(!auth()->user()->is_super_admin)
                            @php
                                $classes = \App\Models\SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get();
                            @endphp
                            @forelse($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} (Grade {{ $class->grade_level }})</option>
                            @empty
                                <option value="" disabled>No classes available. Create classes first.</option>
                            @endforelse
                        @else
                            <option value="" disabled>Please select a branch first</option>
                        @endif
                    </select>
                </div>
                <div>
                    <!-- Empty div for grid alignment -->
                </div>
            </div>

            <!-- Subject Assignment Section -->
            <div id="subject-assignment-section" class="hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-blue-800 mb-3">Assign Subjects</h4>
                    <p class="text-sm text-blue-600 mb-3">Select the subjects this student will take in the assigned
                        class:</p>
                    <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <!-- Subjects will be loaded here dynamically -->
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Password</label>
                    <input type="password" id="student-password"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter password (min 6 chars)" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Confirm Password</label>
                    <input type="password" id="student-password-confirm"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Confirm password" required>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="createStudent()"
                    class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                    Create Student
                </button>
                <button type="button" id="cancel-student-btn"
                    class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <div class="flex flex-wrap gap-4 items-center">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Class:</label>
                <select id="class-filter" class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                    <option value="">All Classes</option>
                    @foreach(\App\Models\SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get() as $class)
                        <option value="{{ $class->name }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search:</label>
                <input type="text" id="search-input" placeholder="Search by name, phone, email, or admission number..."
                    class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500 w-80">
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guardian
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="students-table-body">
                @php
                    $currentBranchId = session('current_branch_id');
                    $students = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId) {
                        if ($currentBranchId) {
                            $query->where('branch_id', $currentBranchId)->where('role', 'student');
                        }
                    })->with(['branches', 'enrollments.schoolClass', 'studentSubjects', 'studentProfile'])->latest()->get();
                @endphp

                @foreach($students as $student)
                    <tr class="student-row" 
                        data-class="{{ $student->enrollments->first()->schoolClass->name ?? '' }}"
                        data-name="{{ strtolower($student->name) }}" 
                        data-email="{{ strtolower($student->email ?? '') }}"
                        data-phone="{{ strtolower($student->phone ?? '') }}"
                        data-admission="{{ strtolower($student->studentProfile->admission_number ?? '') }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span
                                            class="text-green-600 font-semibold text-lg">{{ substr($student->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $student->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $student->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $student->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                @if($student->enrollments->count() > 0)
                                    @foreach($student->enrollments->take(2) as $enrollment)
                                        <span
                                            class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mr-1">{{ $enrollment->schoolClass->name ?? 'N/A' }}</span>
                                    @endforeach
                                    @if($student->enrollments->count() > 2)
                                        <span class="text-xs text-gray-400">+{{ $student->enrollments->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No class assigned</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                @if($student->studentSubjects->count() > 0)
                                    @foreach($student->studentSubjects->take(3) as $subject)
                                        <span
                                            class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded mr-1 mb-1">{{ $subject->name }}</span>
                                    @endforeach
                                    @if($student->studentSubjects->count() > 3)
                                        <span class="text-xs text-gray-400">+{{ $student->studentSubjects->count() - 3 }}
                                            more</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No subjects assigned</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                @if($student->studentProfile)
                                    <div class="text-xs">
                                        <div class="font-medium">{{ $student->studentProfile->guardian_name ?? 'N/A' }}</div>
                                        <div class="text-gray-400">{{ $student->studentProfile->guardian_phone ?? 'N/A' }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editStudent({{ $student->id }})"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button onclick="viewStudentDetails({{ $student->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button onclick="deleteStudent({{ $student->id }})"
                                class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Student Modal -->
<div id="edit-student-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
    style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Edit Student</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="edit-student-form" class="space-y-6">
                <input type="hidden" id="edit-student-id">

                <!-- Header with Student Avatar and Basic Info -->
                <div class="text-center mb-8">
                    <div class="relative group inline-block">
                        <div id="avatar-container-edit" onclick="triggerPhotoUpload('edit')" 
                             class="w-24 h-24 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mb-4 shadow-lg flex items-center justify-center overflow-hidden cursor-pointer relative border-4 border-white">
                            <span class="text-3xl font-bold text-white" id="edit-student-avatar">S</span>
                            <img id="edit-student-photo-preview" src="" class="hidden w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-green-600 mb-2 font-medium">Click to update photo</p>
                        <input type="file" id="edit-student-photo-input" name="profile_photo" class="hidden" accept="image/*" onchange="previewPhoto(event, 'edit')">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2" id="edit-student-name-display">Student Name</h2>
                    <p class="text-gray-600">Edit Student Information</p>
                </div>

                <!-- Personal Information Card -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 shadow-sm border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="edit-student-name"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="edit-student-email"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" id="edit-student-phone"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="edit-student-address" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="Enter address"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Enrolled Date</label>
                                <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-gray-600"
                                    id="edit-student-enrolled">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information Card -->
                <div
                    class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 mb-6 shadow-sm border border-purple-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Guardian Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Name</label>
                            <input type="text" id="edit-student-guardian-name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                placeholder="Enter guardian name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Phone</label>
                            <input type="tel" id="edit-student-guardian-phone"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                placeholder="Enter guardian phone">
                        </div>
                    </div>
                </div>

                <!-- Academic Information Card -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 mb-6 shadow-sm border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Academic Information</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Class</label>
                            <select id="edit-student-class"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                                onchange="loadSubjectsForEditClass()">
                                <option value="">Select class</option>
                                @foreach(\App\Models\SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get() as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} (Grade {{ $class->grade_level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Assignment Section -->
                        <div id="edit-subject-assignment-section" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Subjects</label>
                            <div id="edit-subjects-container"
                                class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <!-- Subjects will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="updateStudent()"
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div id="student-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
    style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Student Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div id="student-details-content" class="space-y-4">
                <!-- Student details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show/hide create student form
            const addStudentBtn = document.getElementById('add-student-btn');
            const createStudentForm = document.getElementById('create-student-form');
            const cancelStudentBtn = document.getElementById('cancel-student-btn');

            if (addStudentBtn && createStudentForm) {
                addStudentBtn.addEventListener('click', function () {
                    createStudentForm.classList.remove('hidden');

                    // Ensure subject section is hidden when form opens
                    const subjectSection = document.getElementById('subject-assignment-section');
                    if (subjectSection) {
                        subjectSection.classList.add('hidden');
                        subjectSection.style.display = 'none';
                    }
                });
            }

            if (cancelStudentBtn && createStudentForm) {
                cancelStudentBtn.addEventListener('click', function () {
                    createStudentForm.classList.add('hidden');

                    // Reset form
                    const studentForm = document.getElementById('student-form');
                    if (studentForm) {
                        studentForm.reset();
                    }

                    // Reset subject section to hidden state
                    const subjectSection = document.getElementById('subject-assignment-section');
                    if (subjectSection) {
                        subjectSection.classList.add('hidden');
                        subjectSection.style.display = 'none';
                    }
                });
            }

            // Filter functionality
            const classFilter = document.getElementById('class-filter');
            const searchInput = document.getElementById('search-input');
            const studentRows = document.querySelectorAll('.student-row');

            function filterStudents() {
                const selectedClass = classFilter.value.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();

                studentRows.forEach(row => {
                    const className = row.dataset.class ? row.dataset.class.toLowerCase() : '';
                    const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                    const email = row.dataset.email ? row.dataset.email.toLowerCase() : '';
                    const phone = row.dataset.phone ? row.dataset.phone.toLowerCase() : '';
                    const admission = row.dataset.admission ? row.dataset.admission.toLowerCase() : '';

                    const matchesClass = !selectedClass || className === selectedClass;
                    const matchesSearch = !searchTerm || 
                                          name.includes(searchTerm) || 
                                          email.includes(searchTerm) || 
                                          phone.includes(searchTerm) || 
                                          admission.includes(searchTerm);

                    if (matchesClass && matchesSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (classFilter) {
                classFilter.addEventListener('change', filterStudents);
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterStudents);
            }

            // Close form when clicking outside
            document.addEventListener('click', function (event) {
                const form = document.getElementById('create-student-form');
                const addBtn = document.getElementById('add-student-btn');

                if (form && !form.contains(event.target) && !addBtn.contains(event.target)) {
                    if (!form.classList.contains('hidden')) {
                        form.classList.add('hidden');
                    }
                }
            });

            // Close modals when clicking outside
            document.addEventListener('click', function (event) {
                const editModal = document.getElementById('edit-student-modal');
                const detailsModal = document.getElementById('student-details-modal');

                if (editModal && !editModal.querySelector('.relative').contains(event.target)) {
                    if (!editModal.classList.contains('hidden')) {
                        closeEditModal();
                    }
                }

                if (detailsModal && !detailsModal.querySelector('.relative').contains(event.target)) {
                    if (!detailsModal.classList.contains('hidden')) {
                        closeDetailsModal();
                    }
                }
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    const editModal = document.getElementById('edit-student-modal');
                    const detailsModal = document.getElementById('student-details-modal');

                    if (editModal && !editModal.classList.contains('hidden')) {
                        closeEditModal();
                    }

                    if (detailsModal && !detailsModal.classList.contains('hidden')) {
                        closeDetailsModal();
                    }
                }
            });
        });

        function triggerPhotoUpload(type) {
            document.getElementById(`${type}-student-photo-input`).click();
        }

        function previewPhoto(event, type) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(`${type}-student-photo-preview`);
                    const placeholder = document.getElementById(type === 'create' ? 'create-student-avatar-placeholder' : 'edit-student-avatar');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        function loadClassesForBranch() {
            const branchId = document.getElementById('student-branch').value;
            const classSelect = document.getElementById('student-class');

            if (!branchId) {
                classSelect.innerHTML = '<option value="">Select class</option>';
                return;
            }

            // Fetch classes for the selected branch
            fetch(`/api/branches/${branchId}/classes`)
                .then(response => response.json())
                .then(data => {
                    let html = '<option value="">Select class</option>';
                    if (data.classes && data.classes.length > 0) {
                        data.classes.forEach(cls => {
                            html += `<option value="${cls.id}">${cls.name} (Grade ${cls.grade_level})</option>`;
                        });
                    }
                    classSelect.innerHTML = html;
                    // Clear subjects when branch changes
                    const subjectSection = document.getElementById('subject-assignment-section');
                    if (subjectSection) {
                        subjectSection.classList.add('hidden');
                        subjectSection.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    classSelect.innerHTML = '<option value="">Error loading classes</option>';
                });
        }

        function loadSubjectsForClass() {
            const classId = document.getElementById('student-class').value;
            const subjectSection = document.getElementById('subject-assignment-section');
            const subjectsContainer = document.getElementById('subjects-container');

            if (!classId) {
                subjectSection.classList.add('hidden');
                subjectSection.style.display = 'none';
                return;
            }

            // Show loading state
            subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div><span class="ml-2 text-blue-600">Loading subjects...</span></div>';
            subjectSection.classList.remove('hidden');
            subjectSection.style.display = 'block';

            // Fetch subjects for the selected class
            fetch(`/api/classes/${classId}/subjects`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.subjects && data.subjects.length > 0) {
                        subjectsContainer.innerHTML = data.subjects.map(subject => `
                            <label class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="subject_ids[]" 
                                       value="${subject.id}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                <span class="ml-3 text-sm font-medium text-gray-700">${subject.name}</span>
                                ${subject.code ? `<span class="ml-2 text-xs text-gray-500">(${subject.code})</span>` : ''}
                            </label>
                        `).join('');
                    } else {
                        subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-gray-500">No subjects available for this class. Please assign subjects to this class first.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-red-500">Error loading subjects. Please try again.</div>';
                });
        }

        function createStudent() {
            const name = document.getElementById('student-name').value;
            const email = document.getElementById('student-email').value;
            const phone = document.getElementById('student-phone').value;
            const dob = document.getElementById('student-dob').value;
            const address = document.getElementById('student-address').value;
            const guardianName = document.getElementById('student-guardian-name').value;
            const guardianPhone = document.getElementById('student-guardian-phone').value;
            const classId = document.getElementById('student-class').value;
            const password = document.getElementById('student-password').value;
            const passwordConfirm = document.getElementById('student-password-confirm').value;
            const branchId = document.getElementById('student-branch') ? document.getElementById('student-branch').value : null;

            if (!name || !email || !password || !passwordConfirm) {
                alert('Please fill in all required fields');
                return;
            }

            if (branchId === null && document.getElementById('student-branch')) {
                alert('Please select a branch');
                return;
            }

            if (password !== passwordConfirm) {
                alert('Passwords do not match');
                return;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return;
            }

            // Get selected subjects
            const selectedSubjects = Array.from(document.querySelectorAll('input[name="subject_ids[]"]:checked'))
                .map(checkbox => parseInt(checkbox.value));

            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('date_of_birth', dob);
            formData.append('address', address);
            formData.append('guardian_name', guardianName);
            formData.append('guardian_phone', guardianPhone);
            if (branchId) formData.append('branch_id', branchId);
            formData.append('class_id', classId);
            formData.append('password', password);
            formData.append('password_confirmation', passwordConfirm);
            
            selectedSubjects.forEach(id => {
                formData.append('subject_ids[]', id);
            });

            const photoInput = document.getElementById('create-student-photo-input');
            if (photoInput.files.length > 0) {
                formData.append('profile_photo', photoInput.files[0]);
            }

            // Send AJAX request to create student
            fetch('/admin/students-api', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student created successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating the student');
                });
        }

        function editStudent(studentId) {
            // Fetch student data and populate modal
            fetch(`/admin/students-api/${studentId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-student-id').value = data.student.id;
                    document.getElementById('edit-student-name').value = data.student.name;
                    document.getElementById('edit-student-email').value = data.student.email;
                    document.getElementById('edit-student-phone').value = data.student.phone || '';
                    document.getElementById('edit-student-address').value = data.student.address || '';
                    document.getElementById('edit-student-guardian-name').value = data.student.guardian_name || '';
                    document.getElementById('edit-student-guardian-phone').value = data.student.guardian_phone || '';
                    document.getElementById('edit-student-class').value = data.student.enrolled_class_id || '';

                    // Handle photo in edit modal
                    const avatarSpan = document.getElementById('edit-student-avatar');
                    const photoPreview = document.getElementById('edit-student-photo-preview');
                    const photoInput = document.getElementById('edit-student-photo-input');
                    
                    // Reset photo input
                    photoInput.value = '';
                    
                    if (data.student.profile_photo) {
                        photoPreview.src = data.student.profile_photo;
                        photoPreview.classList.remove('hidden');
                        avatarSpan.classList.add('hidden');
                    } else {
                        photoPreview.src = '';
                        photoPreview.classList.add('hidden');
                        avatarSpan.classList.remove('hidden');
                        avatarSpan.textContent = data.student.name.charAt(0).toUpperCase();
                    }

                    // Load subjects for the current class and pre-select assigned subjects
                    if (data.student.enrolled_class_id) {
                        loadSubjectsForEditClass();
                        // Store assigned subjects to check them after loading
                        window.assignedSubjects = data.student.assigned_subjects || [];
                    }

                    document.getElementById('edit-student-modal').classList.remove('hidden');
                    document.getElementById('edit-student-name-display').textContent = data.student.name;
                    document.getElementById('edit-student-enrolled').textContent = new Date(data.student.created_at).toLocaleDateString();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching student data');
                });
        }

        function loadSubjectsForEditClass() {
            const classId = document.getElementById('edit-student-class').value;
            const subjectSection = document.getElementById('edit-subject-assignment-section');
            const subjectsContainer = document.getElementById('edit-subjects-container');

            if (!classId) {
                subjectSection.classList.add('hidden');
                return;
            }

            // Show loading state
            subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div><span class="ml-2 text-green-600">Loading subjects...</span></div>';
            subjectSection.classList.remove('hidden');

            // Fetch subjects for the selected class
            fetch(`/api/classes/${classId}/subjects`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.subjects.length > 0) {
                        const assignedSubjects = window.assignedSubjects || [];
                        subjectsContainer.innerHTML = data.subjects.map(subject => {
                            const isChecked = assignedSubjects.includes(subject.id);
                            return `
                                <label class="flex items-center p-2 bg-white border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="edit_subject_ids[]" 
                                           value="${subject.id}"
                                           ${isChecked ? 'checked' : ''}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                                    <span class="ml-2 text-sm font-medium text-gray-700">${subject.name}</span>
                                    ${subject.code ? `<span class="ml-1 text-xs text-gray-500">(${subject.code})</span>` : ''}
                                </label>
                            `;
                        }).join('');
                    } else {
                        subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-gray-500">No subjects available for this class.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    subjectsContainer.innerHTML = '<div class="col-span-full text-center py-4 text-red-500">Error loading subjects. Please try again.</div>';
                });
        }

        function updateStudent() {
            const studentId = document.getElementById('edit-student-id').value;
            const name = document.getElementById('edit-student-name').value;
            const email = document.getElementById('edit-student-email').value;
            const phone = document.getElementById('edit-student-phone').value;
            const address = document.getElementById('edit-student-address').value;
            const guardianName = document.getElementById('edit-student-guardian-name').value;
            const guardianPhone = document.getElementById('edit-student-guardian-phone').value;
            const classId = document.getElementById('edit-student-class').value;

            if (!name) {
                alert('Please fill in all required fields');
                return;
            }

            // Get selected subjects
            const selectedSubjects = Array.from(document.querySelectorAll('input[name="edit_subject_ids[]"]:checked'))
                .map(checkbox => parseInt(checkbox.value));

            const formData = new FormData();
            formData.append('_method', 'PUT'); // Important for Laravel to handle multipart/form-data with PUT
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('guardian_name', guardianName);
            formData.append('guardian_phone', guardianPhone);
            formData.append('class_id', classId);
            
            selectedSubjects.forEach(id => {
                formData.append('subject_ids[]', id);
            });

            const photoInput = document.getElementById('edit-student-photo-input');
            if (photoInput.files.length > 0) {
                formData.append('profile_photo', photoInput.files[0]);
            }

            fetch(`/admin/students-api/${studentId}`, {
                method: 'POST', // Sent as POST with _method=PUT to handle files
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student updated successfully! Subject assignments have been saved.');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the student');
                });
        }

        function viewStudentDetails(studentId) {
            // Fetch student details and populate modal
            fetch(`/admin/students-api/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('student-details-content');
                    content.innerHTML = `
                        <!-- Header with Student Avatar and Basic Info -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mb-4 shadow-lg overflow-hidden border-4 border-white">
                                ${data.student.profile_photo ? 
                                    `<img src="${data.student.profile_photo}" class="w-full h-full object-cover">` : 
                                    `<span class="text-3xl font-bold text-white">${data.student.name.charAt(0).toUpperCase()}</span>`
                                }
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">${data.student.name}</h2>
                            <p class="text-gray-600">Student</p>
                        </div>

                        <!-- Personal Information Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 shadow-sm border border-blue-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <span class="w-20 text-sm font-medium text-gray-600">Name:</span>
                                        <span class="text-gray-800 font-medium">${data.student.name}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-20 text-sm font-medium text-gray-600">Email:</span>
                                        <span class="text-gray-800 font-medium">${data.student.email}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-20 text-sm font-medium text-gray-600">Phone:</span>
                                        <span class="text-gray-800 font-medium">${data.student.phone || 'N/A'}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-20 text-sm font-medium text-gray-600">Date of Birth:</span>
                                        <span class="text-gray-800 font-medium">${data.student.date_of_birth || 'N/A'}</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="w-20 text-sm font-medium text-gray-600">Address:</span>
                                        <span class="text-gray-800 font-medium">${data.student.address || 'N/A'}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-20 text-sm font-medium text-gray-600">Enrolled:</span>
                                        <span class="text-gray-800 font-medium">${new Date(data.student.created_at).toLocaleDateString()}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information Card -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 mb-6 shadow-sm border border-purple-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Guardian Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <span class="w-24 text-sm font-medium text-gray-600">Guardian Name:</span>
                                        <span class="text-gray-800 font-medium">${data.student.guardian_name || 'N/A'}</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <span class="w-24 text-sm font-medium text-gray-600">Guardian Phone:</span>
                                        <span class="text-gray-800 font-medium">${data.student.guardian_phone || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 shadow-sm border border-green-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-600">Enrolled Classes</p>
                                        <p class="text-3xl font-bold text-green-700">${data.student.enrollments?.length || 0}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 shadow-sm border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">Current Status</p>
                                        <p class="text-3xl font-bold text-blue-700">Active</p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enrolled Classes Section -->
                        ${data.student.enrollments && data.student.enrollments.length > 0 ? `
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 mb-6 shadow-sm border border-orange-100">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Enrolled Classes</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${data.student.enrollments.map(enrollment => `
                                    <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-orange-600 font-semibold text-sm">${enrollment.school_class?.name?.charAt(0).toUpperCase() || 'C'}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-800 font-medium">${enrollment.school_class?.name || 'N/A'}</span>
                                            <p class="text-sm text-gray-500">Grade ${enrollment.school_class?.grade_level || 'N/A'} • Enrolled ${new Date(enrollment.created_at).toLocaleDateString()}</p>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}
                    `;

                    document.getElementById('student-details-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching student details');
                });
        }

        function deleteStudent(studentId) {
            if (!confirm('Are you sure you want to delete this student?')) {
                return;
            }

            fetch(`/admin/students-api/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the student');
                });
        }

        function closeEditModal() {
            document.getElementById('edit-student-modal').classList.add('hidden');
            // Reset photo preview
            document.getElementById('edit-student-photo-preview').classList.add('hidden');
            document.getElementById('edit-student-avatar').classList.remove('hidden');
            document.getElementById('edit-student-photo-input').value = '';
        }

        function closeDetailsModal() {
            document.getElementById('student-details-modal').classList.add('hidden');
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Form visibility */
        #create-student-form {
            transition: all 0.3s ease-in-out;
            transform-origin: top;
        }

        #create-student-form.hidden {
            display: none;
            opacity: 0;
            transform: translateY(-10px) scale(0.98);
        }

        #create-student-form:not(.hidden) {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Modal styling */
        #edit-student-modal,
        #student-details-modal {
            transition: all 0.3s ease-in-out;
        }

        #edit-student-modal:not(.hidden),
        #student-details-modal:not(.hidden) {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #edit-student-modal .relative,
        #student-details-modal .relative {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
@endpush
