@props(['teachers' => null, 'branches' => null, 'subjects' => null, 'classes' => null, 'currentAcademicYear' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Manage Teachers</h2>
        <button id="add-teacher-btn"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Teacher
        </button>
    </div>

    <!-- Teacher Creation Form (Hidden by default) -->
    <div id="create-teacher-form" class="mb-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden"
        style="position: relative; z-index: 10;">
        <h3 class="font-bold text-lg mb-4 text-blue-800">Create New Teacher</h3>
        <form id="teacher-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Branch *</label>
                    <select id="teacher-branch"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="">Select Branch</option>
                        @if(isset($branches) && $branches->count() > 0)
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Full Name *</label>
                    <input type="text" id="teacher-name"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter full name" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Email *</label>
                    <input type="email" id="teacher-email"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter email" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Phone Number</label>
                    <input type="tel" id="teacher-phone"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter phone number">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Address</label>
                    <input type="text" id="teacher-address"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter address">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Password *</label>
                    <input type="password" id="teacher-password"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter password (min 6 chars)" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Confirm Password *</label>
                    <input type="password" id="teacher-password-confirm"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Confirm password" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Assign Subjects</label>
                    <div
                        class="max-h-32 overflow-y-auto border-2 border-gray-300 rounded-md p-3 focus-within:border-blue-500">
                        @if(isset($subjects) && $subjects->count() > 0)
                            @foreach($subjects as $subject)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="teacher-subject-{{ $subject->id }}" value="{{ $subject->id }}"
                                        class="teacher-subject-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No subjects available for this branch.</p>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Select multiple subjects to assign to this teacher (optional)
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Assign Classes</label>
                    <div
                        class="max-h-32 overflow-y-auto border-2 border-gray-300 rounded-md p-3 focus-within:border-blue-500">
                        @if(isset($classes) && $classes->count() > 0)
                            @foreach($classes as $class)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="teacher-class-{{ $class->id }}" value="{{ $class->id }}"
                                        class="teacher-class-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $class->name }} (Grade
                                        {{ $class->grade_level }})</span>
                                </label>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No classes available for this branch.</p>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Select multiple classes to assign to this teacher (optional)
                    </p>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="createTeacher()"
                    class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                    Create Teacher
                </button>
                <button type="button" id="cancel-teacher-btn"
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Subject:</label>
                <select id="subject-filter" class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                    <option value="">All Subjects</option>
                    @if(isset($subjects) && $subjects->count() > 0)
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Class:</label>
                <select id="class-filter" class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                    <option value="">All Classes</option>
                    @if(isset($classes) && $classes->count() > 0)
                        @foreach($classes as $class)
                            <option value="{{ $class->name }}">{{ $class->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search:</label>
                <input type="text" id="search-input" placeholder="Search by name or email..."
                    class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Teachers List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classes
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="teachers-table-body">
                @if($teachers && $teachers->count() > 0)
                    @foreach($teachers as $teacher)
                        <tr class="teacher-row cursor-pointer hover:bg-gray-50" data-subject="{{ $teacher->subjects->first()->name ?? '' }}"
                            data-class="{{ $teacher->teachingClasses->first()->name ?? '' }}"
                            data-name="{{ strtolower($teacher->name) }}" data-email="{{ strtolower($teacher->email) }}"
                            onclick="window.location.href='{{ route('admin.teachers.show', $teacher->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span
                                                class="text-blue-600 font-semibold text-lg">{{ substr($teacher->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $teacher->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $teacher->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if($teacher->subjects->count() > 0)
                                        @foreach($teacher->subjects->take(3) as $subject)
                                            <span
                                                class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mr-1">{{ $subject->name }}</span>
                                        @endforeach
                                        @if($teacher->subjects->count() > 3)
                                            <span class="text-xs text-gray-400">+{{ $teacher->subjects->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">No subjects assigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if($teacher->teachingClasses->count() > 0)
                                        @foreach($teacher->teachingClasses->take(2) as $class)
                                            <span
                                                class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded mr-1">{{ $class->name }}</span>
                                        @endforeach
                                        @if($teacher->teachingClasses->count() > 2)
                                            <span class="text-xs text-gray-400">+{{ $teacher->teachingClasses->count() - 2 }}
                                                more</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">No classes assigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                <a href="{{ route('admin.teachers.show', $teacher->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <button onclick="deleteTeacher({{ $teacher->id }})"
                                    class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No teachers found for this branch.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($teachers instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($teachers, 'links') && $teachers->hasPages())
        <div class="mt-6">
            {{ $teachers->links() }}
        </div>
    @endif
</div>

<!-- Edit Teacher Modal -->
<x-modals.teacher-edit-modal />

<!-- Teacher Details Modal -->
<x-modals.teacher-details-modal />

@push('scripts')
    <script>
        function notify(message, type = 'info') {
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
                return;
            }
            console.log(message);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Show/hide create teacher form
            const addTeacherBtn = document.getElementById('add-teacher-btn');
            const createTeacherForm = document.getElementById('create-teacher-form');
            const cancelTeacherBtn = document.getElementById('cancel-teacher-btn');

            if (addTeacherBtn && createTeacherForm) {
                addTeacherBtn.addEventListener('click', function () {
                    createTeacherForm.classList.remove('hidden');
                    console.log('Form should now be visible');
                });
            }

            if (cancelTeacherBtn && createTeacherForm) {
                cancelTeacherBtn.addEventListener('click', function () {
                    createTeacherForm.classList.add('hidden');
                    console.log('Form should now be hidden');

                    // Reset form
                    const teacherForm = document.getElementById('teacher-form');
                    if (teacherForm) {
                        teacherForm.reset();
                    }

                    // Clear all checkboxes
                    document.querySelectorAll('.teacher-class-checkbox, .teacher-subject-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });
            }

            // Filter functionality
            const subjectFilter = document.getElementById('subject-filter');
            const classFilter = document.getElementById('class-filter');
            const searchInput = document.getElementById('search-input');
            const teacherRows = document.querySelectorAll('.teacher-row');

            function filterTeachers() {
                const selectedSubject = subjectFilter.value.toLowerCase();
                const selectedClass = classFilter.value.toLowerCase();
                const searchTerm = searchInput.value.toLowerCase();

                teacherRows.forEach(row => {
                    const subject = row.dataset.subject.toLowerCase();
                    const className = row.dataset.class ? row.dataset.class.toLowerCase() : '';
                    const name = row.dataset.name;
                    const email = row.dataset.email;

                    const matchesSubject = !selectedSubject || subject === selectedSubject;
                    const matchesClass = !selectedClass || className === selectedClass;
                    const matchesSearch = !searchTerm || name.includes(searchTerm) || email.includes(searchTerm);

                    if (matchesSubject && matchesClass && matchesSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (subjectFilter) {
                subjectFilter.addEventListener('change', filterTeachers);
            }

            if (classFilter) {
                classFilter.addEventListener('change', filterTeachers);
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterTeachers);
            }

            // Close form when clicking outside
            document.addEventListener('click', function (event) {
                const form = document.getElementById('create-teacher-form');
                const addBtn = document.getElementById('add-teacher-btn');

                if (form && !form.contains(event.target) && !addBtn.contains(event.target)) {
                    if (!form.classList.contains('hidden')) {
                        form.classList.add('hidden');
                        console.log('Form closed by clicking outside');
                    }
                }
            });

            // Close modals when clicking outside
            document.addEventListener('click', function (event) {
                const editModal = document.getElementById('edit-teacher-modal');
                const detailsModal = document.getElementById('teacher-details-modal');

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
                    const editModal = document.getElementById('edit-teacher-modal');
                    const detailsModal = document.getElementById('teacher-details-modal');

                    if (editModal && !editModal.classList.contains('hidden')) {
                        closeEditModal();
                    }

                    if (detailsModal && !detailsModal.classList.contains('hidden')) {
                        closeDetailsModal();
                    }
                }
            });
        });

        function createTeacher() {
            const branchId = document.getElementById('teacher-branch').value;
            const name = document.getElementById('teacher-name').value;
            const email = document.getElementById('teacher-email').value;
            const phone = document.getElementById('teacher-phone').value;
            const address = document.getElementById('teacher-address').value;
            const password = document.getElementById('teacher-password').value;
            const passwordConfirm = document.getElementById('teacher-password-confirm').value;

            if (!branchId || !name || !email || !password || !passwordConfirm) {
                notify('Please fill in all required fields', 'error');
                return;
            }

            if (password !== passwordConfirm) {
                notify('Passwords do not match', 'error');
                return;
            }

            if (password.length < 6) {
                notify('Password must be at least 6 characters long', 'error');
                return;
            }

            // Get selected subject IDs
            const selectedSubjectIds = [];
            document.querySelectorAll('.teacher-subject-checkbox:checked').forEach(checkbox => {
                selectedSubjectIds.push(parseInt(checkbox.value));
            });

            // Get selected class IDs
            const selectedClassIds = [];
            document.querySelectorAll('.teacher-class-checkbox:checked').forEach(checkbox => {
                selectedClassIds.push(parseInt(checkbox.value));
            });

            // Send AJAX request to create teacher
            fetch('/admin/teachers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    branch_id: branchId,
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
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Validation failed while creating teacher');
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        notify('Teacher created successfully!', 'success');
                        location.reload();
                    } else {
                        notify('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notify(error.message || 'An error occurred while creating the teacher', 'error');
                });
        }





        function deleteTeacher(teacherId) {
            if (!confirm('Are you sure you want to delete this teacher?')) {
                return;
            }

            fetch(`/admin/teachers/${teacherId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notify('Teacher deleted successfully!', 'success');
                        location.reload();
                    } else {
                        notify('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notify('An error occurred while deleting the teacher', 'error');
                });
        }

    </script>
@endpush

@push('styles')
    <style>
        /* Form visibility */
        #create-teacher-form {
            transition: all 0.3s ease-in-out;
            transform-origin: top;
        }

        #create-teacher-form.hidden {
            display: none;
            opacity: 0;
            transform: translateY(-10px) scale(0.98);
        }

        #create-teacher-form:not(.hidden) {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Modal styling */
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
    </style>
@endpush
