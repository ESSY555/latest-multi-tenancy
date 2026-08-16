@props(['classes' => null, 'currentAcademicYear' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Branch Class Management</h2>
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
                    <input type="text" id="class-academic-year" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="e.g., 2024-2025" value="{{ $currentAcademicYear ?? '' }}">
                </div>
                <div>
                    <!-- Empty div for grid alignment -->
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teachers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($classes as $class)
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
                            <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $class->enrollments->count() }} students</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            @if($class->teachers->count() > 0)
                                @foreach($class->teachers->take(2) as $teacher)
                                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded mr-1">{{ $teacher->name }}</span>
                                @endforeach
                                @if($class->teachers->count() > 2)
                                    <span class="text-xs text-gray-400">+{{ $class->teachers->count() - 2 }} more</span>
                                @endif
                            @else
                                <span class="text-gray-400">No teachers assigned</span>
                            @endif
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

    @if($classes instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($classes, 'links') && $classes->hasPages())
        <div class="mt-6">
            {{ $classes->links() }}
        </div>
    @endif
</div>

<!-- Edit Class Modal -->
<div id="edit-class-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Class</h3>
            <form id="edit-class-form" class="space-y-4">
                <input type="hidden" id="edit-class-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class Name</label>
                    <input type="text" id="edit-class-name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Grade Level</label>
                    <input type="text" id="edit-class-grade-level" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <input type="text" id="edit-class-academic-year" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="button" onclick="updateClass()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Class Details Modal -->
<div id="class-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Class Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="class-details-content" class="space-y-4">
                <!-- Class details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide create class form
    const addClassBtn = document.getElementById('add-class-btn');
    const createClassForm = document.getElementById('create-class-form');
    const cancelClassBtn = document.getElementById('cancel-class-btn');

    if (addClassBtn && createClassForm) {
        addClassBtn.addEventListener('click', function() {
            createClassForm.classList.remove('hidden');
        });
    }

    if (cancelClassBtn && createClassForm) {
        cancelClassBtn.addEventListener('click', function() {
            createClassForm.classList.add('hidden');
            
            // Reset form
            const classForm = document.getElementById('class-form');
            if (classForm) {
                classForm.reset();
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
            }
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const editModal = document.getElementById('edit-class-modal');
        const detailsModal = document.getElementById('class-details-modal');
        
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
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const editModal = document.getElementById('edit-class-modal');
            const detailsModal = document.getElementById('class-details-modal');
            
            if (editModal && !editModal.classList.contains('hidden')) {
                closeEditModal();
            }
            
            if (detailsModal && !detailsModal.classList.contains('hidden')) {
                closeDetailsModal();
            }
        }
    });
});

function createClass() {
    const name = document.getElementById('class-name').value;
    const gradeLevel = document.getElementById('class-grade-level').value;
    const academicYear = document.getElementById('class-academic-year').value;

    if (!name) {
        alert('Please fill in the class name');
        return;
    }

    // Send AJAX request to create class
    fetch('/admin/classes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            grade_level: gradeLevel,
            academic_year: academicYear
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Class created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the class');
    });
}

function editClass(classId) {
    // Fetch class data and populate modal
    fetch(`/admin/classes/${classId}/edit`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit-class-id').value = data.class.id;
        document.getElementById('edit-class-name').value = data.class.name;
        document.getElementById('edit-class-grade-level').value = data.class.grade_level || '';
        document.getElementById('edit-class-academic-year').value = data.class.academic_year || '';
        
        document.getElementById('edit-class-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching class data');
    });
}

function updateClass() {
    const classId = document.getElementById('edit-class-id').value;
    const name = document.getElementById('edit-class-name').value;
    const gradeLevel = document.getElementById('edit-class-grade-level').value;
    const academicYear = document.getElementById('edit-class-academic-year').value;

    if (!name) {
        alert('Please fill in the class name');
        return;
    }

    fetch(`/admin/classes/${classId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            grade_level: gradeLevel,
            academic_year: academicYear
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Class updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the class');
    });
}

function viewClassDetails(classId) {
    // Fetch class details and populate modal
    fetch(`/admin/classes/${classId}`)
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('class-details-content');
        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Class Information</h4>
                    <div class="space-y-2">
                        <div><span class="font-medium">Name:</span> ${data.class.name}</div>
                        <div><span class="font-medium">Grade Level:</span> ${data.class.grade_level || 'N/A'}</div>
                        <div><span class="font-medium">Academic Year:</span> ${data.class.academic_year || 'N/A'}</div>
                        <div><span class="font-medium">Created:</span> ${new Date(data.class.created_at).toLocaleDateString()}</div>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Statistics</h4>
                    <div class="space-y-2">
                        <div><span class="font-medium">Students:</span> ${data.class.enrollments?.length || 0}</div>
                        <div><span class="font-medium">Teachers:</span> ${data.class.teachers?.length || 0}</div>
                    </div>
                </div>
            </div>
            ${data.class.enrollments && data.class.enrollments.length > 0 ? `
            <div class="mt-6">
                <h4 class="font-medium text-gray-900 mb-3">Enrolled Students</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.class.enrollments.map(enrollment => `
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <div class="font-medium">${enrollment.student?.name || 'N/A'}</div>
                            <div class="text-sm text-gray-500">${enrollment.student?.email || 'N/A'}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}
            ${data.class.teachers && data.class.teachers.length > 0 ? `
            <div class="mt-6">
                <h4 class="font-medium text-gray-900 mb-3">Assigned Teachers</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.class.teachers.map(teacher => `
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <div class="font-medium">${teacher.name}</div>
                            <div class="text-sm text-gray-500">${teacher.email}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}
        `;
        
        document.getElementById('class-details-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching class details');
    });
}

function deleteClass(classId) {
    if (!confirm('Are you sure you want to delete this class? This action cannot be undone.')) {
        return;
    }

    fetch(`/admin/classes/${classId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Class deleted successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the class');
    });
}

function closeEditModal() {
    document.getElementById('edit-class-modal').classList.add('hidden');
}

function closeDetailsModal() {
    document.getElementById('class-details-modal').classList.add('hidden');
}
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

/* Modal styling */
#edit-class-modal,
#class-details-modal {
    transition: all 0.3s ease-in-out;
}

#edit-class-modal:not(.hidden),
#class-details-modal:not(.hidden) {
    display: flex;
    align-items: center;
    justify-content: center;
}

#edit-class-modal .relative,
#class-details-modal .relative {
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

