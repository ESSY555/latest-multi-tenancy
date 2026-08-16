@props(['classes' => null, 'currentAcademicYear' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold">Class Management</h2>
            @if($currentAcademicYear)
            <p class="text-sm text-gray-600 mt-2">
                Active Academic Year: <span class="font-semibold text-blue-600">{{ $currentAcademicYear->name }}</span>
                <span class="text-xs text-gray-500">({{ $currentAcademicYear->start_date->format('M Y') }} - {{ $currentAcademicYear->end_date->format('M Y') }})</span>
            </p>
            @else
            <p class="text-sm text-yellow-600 mt-2">
                ⚠️ No active academic year set. Please set one in Academy Section.
            </p>
            @endif
        </div>
        <button id="add-class-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Class
        </button>
    </div>

    <!-- Class Creation Form (Hidden by default) -->
    <div id="create-class-form" class="mb-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden" style="position: relative; z-index: 10;">
        <h3 class="font-bold text-lg mb-4 text-blue-800">Create New Class</h3>
        <form id="class-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Class Name</label>
                    <input type="text" id="class-name" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Enter class name" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Grade Level</label>
                    <input type="text" id="class-grade-level" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="e.g., Grade 10, Year 11">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Academic Year</label>
                    <input type="text" id="class-academic-year" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500 bg-gray-100" placeholder="e.g., 2024-2025" value="{{ $currentAcademicYear->name ?? '' }}" readonly>
                    <input type="hidden" id="class-academic-year-id" value="{{ $currentAcademicYear->id ?? '' }}">
                    <p class="text-xs text-gray-500 mt-1">Automatically set to active academic year</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Branch</label>
                    <select id="class-branch" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="">Select branch</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="createClass()" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                    Create Class
                </button>
                <button type="button" id="cancel-class-btn" class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Classes List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach(\App\Models\SchoolClass::with('branch')->latest()->get() as $class)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $class->grade_level ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $class->academic_year ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">{{ $class->branch->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="inline-flex gap-2">
                            <button onclick="viewClassDetails({{ $class->id }})" type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>View</span>
                            </button>
                            <button onclick="editClass({{ $class->id }})" type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-indigo-200 text-indigo-700 hover:bg-indigo-50 hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13v7h7l8.485-8.485a2.5 2.5 0 10-3.536-3.536L7.464 16.464A2 2 0 016 17H4v-2a2 2 0 01.586-1.414l8.95-8.95" />
                                </svg>
                                <span>Edit</span>
                            </button>
                            <button onclick="deleteClass({{ $class->id }})" type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-red-200 text-red-700 hover:bg-red-300 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2-2h6a2 2 0 012 2v0H5v0a2 2 0 012-2z" />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Edit Class Modal (Hidden by default) -->
    <div id="edit-class-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 max-w-2xl mx-auto p-5 border w-8/12 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-blue-800">Edit Class</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form id="edit-class-form" class="space-y-4">
                    <input type="hidden" id="edit-class-id">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Class Name</label>
                        <input type="text" id="edit-class-name" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Enter class name" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Grade Level</label>
                        <input type="text" id="edit-class-grade-level" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="e.g., Grade 10, Year 11">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Academic Year</label>
                        <input type="text" id="edit-class-academic-year" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="e.g., 2024-2025">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Branch</label>
                        <select id="edit-class-branch" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                            <option value="">Select branch</option>
                            @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="updateClass()" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                            Update Class
                        </button>
                        <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Class Details Modal (Hidden by default) -->
    <div id="view-class-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 max-w-4xl mx-auto p-5 border w-11/12 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-2xl text-blue-800">Class Details</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Class Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Basic Information</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Class Name</label>
                                <p id="view-class-name" class="text-lg font-semibold text-gray-900"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Grade Level</label>
                                <p id="view-class-grade-level" class="text-gray-900"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Academic Year</label>
                                <p id="view-class-academic-year" class="text-gray-900"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Branch</label>
                                <p id="view-class-branch" class="text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Statistics</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Students</label>
                                <p id="view-class-students" class="text-lg font-semibold text-blue-600"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Teachers</label>
                                <p id="view-class-teachers" class="text-lg font-semibold text-green-600"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Subjects</label>
                                <p id="view-class-subjects" class="text-lg font-semibold text-purple-600"></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Created Date</label>
                                <p id="view-class-created" class="text-gray-900"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students List -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Enrolled Students</h4>
                    <div id="view-class-students-list" class="space-y-2">
                        <!-- Students will be populated here -->
                    </div>
                </div>

                <!-- Teachers List -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Assigned Teachers</h4>
                    <div id="view-class-teachers-list" class="space-y-2">
                        <!-- Teachers will be populated here -->
                    </div>
                </div>

                <!-- Subjects List -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Class Subjects</h4>
                    <div id="view-class-subjects-list" class="space-y-2">
                        <!-- Subjects will be populated here -->
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button onclick="closeViewModal()" class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Class management functions
function showCreateClassForm() {
    console.log('showCreateClassForm called - showing form');
    const form = document.getElementById('create-class-form');
    if (form) {
        form.classList.remove('hidden');
        console.log('Form is now visible');
        console.log('Form classes after:', form.className);
        console.log('Form has hidden class:', form.classList.contains('hidden'));
    } else {
        console.error('Form element not found!');
    }
}

function hideCreateClassForm() {
    console.log('hideCreateClassForm called - hiding form');
    const form = document.getElementById('create-class-form');
    if (form) {
        form.classList.add('hidden');
        console.log('Form is now hidden');
    } else {
        console.error('Form element not found!');
    }
}

function createClass() {
    console.log('createClass function called!');
    
    // Get form values
    const name = document.getElementById('class-name').value.trim();
    const gradeLevel = document.getElementById('class-grade-level').value.trim();
    const academicYear = document.getElementById('class-academic-year').value.trim();
    const branchId = document.getElementById('class-branch').value;
    
    console.log('Form values:', { name, gradeLevel, academicYear, branchId });
    
    // Simple validation
    if (!name || !branchId) {
        alert('Class name and branch are required!');
        return;
    }
    
    // Create form data
    const formData = {
        name: name,
        grade_level: gradeLevel,
        academic_year: academicYear,
        branch_id: branchId
    };
    
    console.log('Sending data:', formData);
    
    // Send request
    fetch('/classes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            alert('Class created successfully!');
            document.getElementById('class-form').reset();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating class. Check console for details.');
    });
}

function viewClassDetails(classId) {
    console.log('viewClassDetails called for class ID:', classId);
    
    // Fetch class details and show modal
    fetch(`/classes/${classId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Class details received:', data);
        
        if (data.success && data.class) {
            const classData = data.class;
            
            // Populate basic information
            document.getElementById('view-class-name').textContent = classData.name;
            document.getElementById('view-class-grade-level').textContent = classData.grade_level || 'N/A';
            document.getElementById('view-class-academic-year').textContent = classData.academic_year || 'N/A';
            document.getElementById('view-class-branch').textContent = classData.branch?.name || 'N/A';
            
            // Populate statistics
            document.getElementById('view-class-students').textContent = classData.enrollments?.length || 0;
            document.getElementById('view-class-teachers').textContent = classData.teachers?.length || 0;
            document.getElementById('view-class-subjects').textContent = classData.subjects?.length || 0;
            document.getElementById('view-class-created').textContent = classData.created_at ? new Date(classData.created_at).toLocaleDateString() : 'N/A';
            
            // Populate students list
            const studentsList = document.getElementById('view-class-students-list');
            if (classData.enrollments && classData.enrollments.length > 0) {
                studentsList.innerHTML = classData.enrollments.map(enrollment => {
                    const student = enrollment.student || {};
                    const enrollmentDate = enrollment.created_at ? new Date(enrollment.created_at).toLocaleDateString() : 'N/A';
                    return `
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                            <div>
                                <div class="font-medium text-gray-900">${student.name || 'N/A'}</div>
                                <div class="text-sm text-gray-500">${student.email || 'N/A'}</div>
                            </div>
                            <span class="text-sm text-gray-600">Enrolled: ${enrollmentDate}</span>
                        </div>
                    `;
                }).join('');
            } else {
                studentsList.innerHTML = '<p class="text-gray-500 text-center py-4">No students enrolled in this class.</p>';
            }
            
            // Populate teachers list
            const teachersList = document.getElementById('view-class-teachers-list');
            if (classData.teachers && classData.teachers.length > 0) {
                teachersList.innerHTML = classData.teachers.map(teacher => {
                    const teacherName = teacher.name || 'N/A';
                    const teacherEmail = teacher.email || 'N/A';
                    return `
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                            <div>
                                <div class="font-medium text-gray-900">${teacherName}</div>
                                <div class="text-sm text-gray-500">${teacherEmail}</div>
                            </div>
                            <span class="text-sm text-gray-600">Teacher</span>
                        </div>
                    `;
                }).join('');
            } else {
                teachersList.innerHTML = '<p class="text-gray-500 text-center py-4">No teachers assigned to this class.</p>';
            }
            
            // Populate subjects list
            const subjectsList = document.getElementById('view-class-subjects-list');
            if (classData.subjects && classData.subjects.length > 0) {
                subjectsList.innerHTML = classData.subjects.map(subject => {
                    const subjectName = subject.name || 'N/A';
                    const subjectDescription = subject.description || 'No description';
                    return `
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                            <div>
                                <div class="font-medium text-gray-900">${subjectName}</div>
                                <div class="text-sm text-gray-500">${subjectDescription}</div>
                            </div>
                            <span class="text-sm text-gray-600">Subject</span>
                        </div>
                    `;
                }).join('');
            } else {
                subjectsList.innerHTML = '<p class="text-gray-500 text-center py-4">No subjects assigned to this class.</p>';
            }
            
            // Show modal
            document.getElementById('view-class-modal').classList.remove('hidden');
            console.log('View modal should now be visible');
        } else {
            alert('Error: ' + (data.message || 'Failed to load class details'));
        }
    })
    .catch(error => {
        console.error('Error fetching class details:', error);
        alert('Error loading class details: ' + error.message);
    });
}

function editClass(classId) {
    console.log('editClass called for class ID:', classId);
    
    // Fetch class data and show edit modal
    fetch(`/classes/${classId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Class data received:', data);
        
        if (data.success && data.class) {
            // Populate edit form
            document.getElementById('edit-class-id').value = data.class.id;
            document.getElementById('edit-class-name').value = data.class.name;
            document.getElementById('edit-class-grade-level').value = data.class.grade_level || '';
            document.getElementById('edit-class-academic-year').value = data.class.academic_year || '';
            document.getElementById('edit-class-branch').value = data.class.branch_id || '';
            
            // Show modal
            document.getElementById('edit-class-modal').classList.remove('hidden');
            console.log('Edit modal should now be visible');
        } else {
            alert('Error: ' + (data.message || 'Failed to load class data'));
        }
    })
    .catch(error => {
        console.error('Error fetching class data:', error);
        alert('Error loading class data: ' + error.message);
    });
}

function updateClass() {
    console.log('updateClass function called!');
    
    // Get form values
    const classId = document.getElementById('edit-class-id').value;
    const name = document.getElementById('edit-class-name').value.trim();
    const gradeLevel = document.getElementById('edit-class-grade-level').value.trim();
    const academicYear = document.getElementById('edit-class-academic-year').value.trim();
    const branchId = document.getElementById('edit-class-branch').value;
    
    console.log('Update form values:', { classId, name, gradeLevel, academicYear, branchId });
    
    // Basic validation
    if (!name || !branchId) {
        alert('Class name and branch are required!');
        return;
    }
    
    // Create form data
    const formData = {
        name: name,
        grade_level: gradeLevel,
        academic_year: academicYear,
        branch_id: branchId
    };
    
    console.log('Sending update data:', formData);
    
    // Send update request
    fetch(`/classes/${classId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Update response:', data);
        if (data.success) {
            alert('Class updated successfully!');
            closeEditModal();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error updating class:', error);
        alert('Error updating class. Check console for details.');
    });
}

function closeEditModal() {
    console.log('closeEditModal called');
    const modal = document.getElementById('edit-class-modal');
    if (modal) {
        modal.classList.add('hidden');
        console.log('Edit modal should now be hidden');
        
        // Reset form
        const editForm = document.getElementById('edit-class-form');
        if (editForm) {
            editForm.reset();
        }
    }
}

function closeViewModal() {
    console.log('closeViewModal called');
    const modal = document.getElementById('view-class-modal');
    if (modal) {
        modal.classList.add('hidden');
        console.log('View modal should now be hidden');
    }
}

function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this class? This action cannot be undone.')) {
        fetch(`/classes/${classId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage('Class deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                const errorMsg = data.message || 'Unknown error occurred';
                console.error('Server Error:', errorMsg);
                showMessage('Error deleting class: ' + errorMsg, 'error');
            }
        })
        .catch(error => {
            console.error('Delete Fetch Error:', error);
            const errorMsg = 'Network error or server unavailable';
            showMessage('Error deleting class: ' + errorMsg, 'error');
        });
    }
}

// Message display function
function showMessage(message, type = 'info') {
    console.log(`${type.toUpperCase()}: ${message}`);
    
    // Simple fallback if the fancy message system fails
    if (type === 'error') {
        alert('ERROR: ' + message);
        return;
    }
    if (type === 'success') {
        alert('SUCCESS: ' + message);
        return;
    }
    
    // Remove existing message if any
    const existingMessage = document.getElementById('class-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.id = 'class-message';
    messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : type === 'error' 
            ? 'bg-red-500 text-white' 
            : 'bg-blue-500 text-white'
    }`;
    
    messageDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                        : type === 'error'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                    }
                </svg>
                <span class="font-medium">${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 5000);
}

// Initialize class management component
document.addEventListener('DOMContentLoaded', function() {
    // Setup Add Class button functionality
    const addClassBtn = document.getElementById('add-class-btn');
    if (addClassBtn) {
        console.log('Add Class button found and event listener added');
        addClassBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add Class button clicked');
            const form = document.getElementById('create-class-form');
            if (form) {
                console.log('Form found, removing hidden class');
                form.classList.remove('hidden');
                console.log('Form classes after removal:', form.className);
                console.log('Form has hidden class:', form.classList.contains('hidden'));
                
                // Focus on first input field
                const firstInput = form.querySelector('input');
                if (firstInput) {
                    firstInput.focus();
                    console.log('Focused on first input field');
                }
                
                // Show a subtle success message
                console.log('✅ Class creation form is now visible!');
            } else {
                console.error('Create class form not found!');
            }
        });
    } else {
        console.error('Add Class button not found!');
    }
    
    // Setup Cancel button functionality
    const cancelClassBtn = document.getElementById('cancel-class-btn');
    if (cancelClassBtn) {
        cancelClassBtn.addEventListener('click', function() {
            console.log('Cancel button clicked');
            const form = document.getElementById('create-class-form');
            if (form) {
                form.classList.add('hidden');
                console.log('Form should now be hidden');
                
                // Reset form
                const classForm = document.getElementById('class-form');
                if (classForm) {
                    classForm.reset();
                }
            }
        });
    }
    
    // Close form when clicking outside
    document.addEventListener('click', function(event) {
        const form = document.getElementById('create-class-form');
        const addBtn = document.getElementById('add-class-btn');
        
        if (form && !form.contains(event.target) && !addBtn.contains(event.target)) {
            if (!form.classList.contains('hidden')) {
                form.classList.add('hidden');
                console.log('Form closed by clicking outside');
            }
        }
    });
    
    // Close edit modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('edit-class-modal');
        const modalContent = modal ? modal.querySelector('.relative') : null;
        
        if (modal && modalContent && !modalContent.contains(event.target)) {
            if (!modal.classList.contains('hidden')) {
                closeEditModal();
                console.log('Edit modal closed by clicking outside');
            }
        }
    });
    
    // Close edit modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('edit-class-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeEditModal();
                console.log('Edit modal closed with Escape key');
            }
        }
    });

    // Close view modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('view-class-modal');
        const modalContent = modal ? modal.querySelector('.relative') : null;
        
        if (modal && modalContent && !modalContent.contains(event.target)) {
            if (!modal.classList.contains('hidden')) {
                closeViewModal();
                console.log('View modal closed by clicking outside');
            }
        }
    });

    // Close view modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('view-class-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeViewModal();
                console.log('View modal closed with Escape key');
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Form visibility */
#create-class-form {
    transition: all 0.3s ease-in-out;
    transform-origin: top;
}

#create-class-form.hidden {
    display: none;
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
}

#create-class-form:not(.hidden) {
    display: block;
    opacity: 1;
    transform: translateY(0) scale(1);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Edit modal styling */
#edit-class-modal {
    transition: all 0.3s ease-in-out;
}

#edit-class-modal:not(.hidden) {
    display: flex;
    align-items: center;
    justify-content: center;
}

#edit-class-modal .relative {
    animation: modalSlideIn 0.3s ease-out;
}

/* View modal styling */
#view-class-modal {
    transition: all 0.3s ease-in-out;
}

#view-class-modal:not(.hidden) {
    display: flex;
    align-items: center;
    justify-content: center;
}

#view-class-modal .relative {
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

