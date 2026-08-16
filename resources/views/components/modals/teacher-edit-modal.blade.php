@props(['teacher' => null])

<!-- Edit Teacher Modal -->
<div id="edit-teacher-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Edit Teacher</h3>
                <button onclick="closeEditTeacherModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="edit-teacher-form" class="space-y-6">
                <input type="hidden" id="edit-teacher-id">
                
                <!-- Header with Teacher Avatar and Basic Info -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white" id="edit-teacher-avatar">T</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2" id="edit-teacher-name-display">Teacher Name</h2>
                    <p class="text-gray-600">Edit Teacher Information</p>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="edit-teacher-name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="edit-teacher-email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" id="edit-teacher-phone" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="edit-teacher-address" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Enter address"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Joined Date</label>
                                <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-gray-600" id="edit-teacher-joined">
                                    Loading...
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password (Leave blank to keep current)</label>
                                <input type="password" id="edit-teacher-password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Enter new password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" id="edit-teacher-password-confirm" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teaching Information Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 shadow-sm border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600">Current Classes</p>
                                <p class="text-3xl font-bold text-green-700" id="edit-teacher-classes-count">0</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 shadow-sm border border-purple-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600">Current Subjects</p>
                                <p class="text-3xl font-bold text-purple-700" id="edit-teacher-subjects-count">0</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assign Subjects Section -->
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 mb-6 shadow-sm border border-orange-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Assign Subjects</h3>
                    </div>
                    <div class="max-h-40 overflow-y-auto border border-orange-200 rounded-lg p-4 bg-white">
                        @foreach(\App\Models\Subject::where('branch_id', session('current_branch_id'))->orderBy('name')->get() as $subject)
                            <label class="flex items-center space-x-3 py-2 hover:bg-orange-50 rounded-lg px-2 transition-colors">
                                <input type="checkbox" name="edit-subject-{{ $subject->id }}" value="{{ $subject->id }}" class="edit-subject-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500 focus:ring-2">
                                <span class="text-sm text-gray-700 font-medium">{{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-3 text-center">Select multiple subjects to assign to this teacher</p>
                </div>

                <!-- Assign Classes Section -->
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-6 mb-6 shadow-sm border border-teal-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Assign Classes</h3>
                    </div>
                    <div class="max-h-40 overflow-y-auto border border-teal-200 rounded-lg p-4 bg-white">
                        @foreach(\App\Models\SchoolClass::where('branch_id', session('current_branch_id'))->orderBy('name')->get() as $class)
                            <label class="flex items-center space-x-3 py-2 hover:bg-teal-50 rounded-lg px-2 transition-colors">
                                <input type="checkbox" name="edit-class-{{ $class->id }}" value="{{ $class->id }}" class="edit-class-checkbox rounded border-gray-300 text-teal-600 focus:ring-teal-500 focus:ring-2">
                                <span class="text-sm text-gray-700 font-medium">{{ $class->name }} (Grade {{ $class->grade_level }})</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-3 text-center">Select multiple classes to assign to this teacher</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditTeacherModal()" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="button" onclick="updateTeacher()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Update Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editTeacher(teacherId) {
    // Fetch teacher data and populate modal
    fetch(`/admin/teachers/${teacherId}/edit`)
    .then(response => response.json())
    .then(data => {
        const teacher = data.teacher;
        
        // Update avatar and display name
        document.getElementById('edit-teacher-avatar').textContent = teacher.name.charAt(0).toUpperCase();
        document.getElementById('edit-teacher-name-display').textContent = teacher.name;
        
        // Populate form fields
        document.getElementById('edit-teacher-id').value = teacher.id;
        document.getElementById('edit-teacher-name').value = teacher.name;
        document.getElementById('edit-teacher-email').value = teacher.email;
        document.getElementById('edit-teacher-phone').value = teacher.phone || '';
        document.getElementById('edit-teacher-address').value = teacher.address || '';
        document.getElementById('edit-teacher-password').value = '';
        document.getElementById('edit-teacher-password-confirm').value = '';
        document.getElementById('edit-teacher-joined').textContent = new Date(teacher.created_at).toLocaleDateString();
        
        // Update statistics
        document.getElementById('edit-teacher-classes-count').textContent = data.assigned_class_ids?.length || 0;
        document.getElementById('edit-teacher-subjects-count').textContent = data.assigned_subject_ids?.length || 0;
        
        // Clear all checkboxes first
        document.querySelectorAll('.edit-class-checkbox, .edit-subject-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Check the boxes for currently assigned subjects
        if (data.assigned_subject_ids && data.assigned_subject_ids.length > 0) {
            data.assigned_subject_ids.forEach(subjectId => {
                const checkbox = document.querySelector(`input[name="edit-subject-${subjectId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
        
        // Check the boxes for currently assigned classes
        if (data.assigned_class_ids && data.assigned_class_ids.length > 0) {
            data.assigned_class_ids.forEach(classId => {
                const checkbox = document.querySelector(`input[name="edit-class-${classId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
        
        document.getElementById('edit-teacher-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching teacher data');
    });
}

function updateTeacher() {
    const teacherId = document.getElementById('edit-teacher-id').value;
    const name = document.getElementById('edit-teacher-name').value;
    const email = document.getElementById('edit-teacher-email').value;
    const phone = document.getElementById('edit-teacher-phone').value;
    const address = document.getElementById('edit-teacher-address').value;
    const password = document.getElementById('edit-teacher-password').value;
    const passwordConfirm = document.getElementById('edit-teacher-password-confirm').value;

    if (!name || !email) {
        alert('Please fill in all required fields');
        return;
    }

    if (password && password !== passwordConfirm) {
        alert('Passwords do not match');
        return;
    }

    if (password && password.length < 6) {
        alert('Password must be at least 6 characters long');
        return;
    }

    // Get selected subject IDs
    const selectedSubjectIds = [];
    document.querySelectorAll('.edit-subject-checkbox:checked').forEach(checkbox => {
        selectedSubjectIds.push(parseInt(checkbox.value));
    });

    // Get selected class IDs
    const selectedClassIds = [];
    document.querySelectorAll('.edit-class-checkbox:checked').forEach(checkbox => {
        selectedClassIds.push(parseInt(checkbox.value));
    });

    fetch(`/admin/teachers/${teacherId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            email: email,
            phone: phone,
            address: address,
            subject_ids: selectedSubjectIds,
            class_ids: selectedClassIds,
            password: password,
            password_confirmation: passwordConfirm
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Teacher updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the teacher');
    });
}

function closeEditTeacherModal() {
    document.getElementById('edit-teacher-modal').classList.add('hidden');
    
    // Clear all checkboxes
    document.querySelectorAll('.edit-class-checkbox, .edit-subject-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const editModal = document.getElementById('edit-teacher-modal');
    
    if (editModal && !editModal.querySelector('.relative').contains(event.target)) {
        if (!editModal.classList.contains('hidden')) {
            closeEditTeacherModal();
        }
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const editModal = document.getElementById('edit-teacher-modal');
        
        if (editModal && !editModal.classList.contains('hidden')) {
            closeEditTeacherModal();
        }
    }
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced modal styling */
#edit-teacher-modal .relative {
    max-height: 90vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

#edit-teacher-modal .relative::-webkit-scrollbar {
    width: 6px;
}

#edit-teacher-modal .relative::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 3px;
}

#edit-teacher-modal .relative::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

#edit-teacher-modal .relative::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Form styling enhancements */
#edit-teacher-form input:focus,
#edit-teacher-form textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Checkbox styling */
.edit-subject-checkbox,
.edit-class-checkbox {
    accent-color: #3b82f6;
}

/* Modal animation */
#edit-teacher-modal {
    transition: all 0.3s ease-in-out;
}

#edit-teacher-modal:not(.hidden) {
    display: flex;
    align-items: center;
    justify-content: center;
}

#edit-teacher-modal .relative {
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

/* Hover effects for checkboxes */
.edit-subject-checkbox:hover + span,
.edit-class-checkbox:hover + span {
    color: #1f2937;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #edit-teacher-modal .relative {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
    
    .grid.grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

