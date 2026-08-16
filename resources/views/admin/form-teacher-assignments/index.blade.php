@extends('layouts.dashboard')

@section('title', 'Form Teacher Assignments')

@section('dashboard')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="md:text-3xl font-bold text-gray-900">Form Teacher Assignments</h1>
            <button onclick="openCreateModal()"
                class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Assign Form Teacher
            </button>
        </div>

            <!-- Form Teacher Assignments Table -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Current Assignments</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                                </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="assignmentsTable">
                                    @foreach($formTeacherAssignments as $assignment)
                                        <tr data-assignment-id="{{ $assignment->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $assignment->user->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $assignment->schoolClass->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $assignment->branch->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $assignment->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assignment->start_date ? $assignment->start_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assignment->end_date ? $assignment->end_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="openEditModal({{ $assignment->id }})"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                                <button onclick="toggleStatus({{ $assignment->id }})" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    {{ $assignment->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                                <button onclick="deleteAssignment({{ $assignment->id }})"
                                                    class="text-red-600 hover:text-red-900">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                                </div>
                                </div>

            @if($formTeacherAssignments->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">No form teacher assignments found.</p>
                </div>
            @endif

            @if($formTeacherAssignments instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($formTeacherAssignments, 'links') && $formTeacherAssignments->hasPages())
                <div class="mt-6">
                    {{ $formTeacherAssignments->links() }}
                </div>
            @endif
            </div>
            </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-10 sm:top-20 mx-auto my-8 p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Form Teacher</h3>
                <form id="createForm">
                    @csrf
                    <div class="mb-4">
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                        <select id="branch_id" name="branch_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-4">
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher</label>
                        <select id="teacher_id" name="teacher_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-4">
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select id="class_id" name="class_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="start_date" name="start_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date
                            (Optional)</label>
                        <input type="date" id="end_date" name="end_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Assign
                        </button>
                        </div>
                        </form>
                        </div>
                        </div>
                        </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-10 sm:top-20 mx-auto my-8 p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Form Teacher Assignment</h3>
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_assignment_id" name="assignment_id">
                    <input type="hidden" id="edit_branch_id" name="branch_id">
                    <div class="mb-4">
                        <label for="edit_branch_name" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                        <input type="text" id="edit_branch_name" disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                        </div>
                        <div class="mb-4">
                            <label for="edit_teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher</label>
                        <select id="edit_teacher_id" name="teacher_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select id="edit_class_id" name="class_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-4">
                            <label for="edit_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="edit_start_date" name="start_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                        <label for="edit_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date
                            (Optional)</label>
                        <input type="date" id="edit_end_date" name="end_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                        <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes
                            (Optional)</label>
                        <textarea id="edit_notes" name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                            <input type="checkbox" id="edit_is_active" name="is_active"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            </div>
                            <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Update
                        </button>
                        </div>
                        </form>
                        </div>
                        </div>
                        </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

                function closeCreateModal() {
                    document.getElementById('createModal').classList.add('hidden');
                    document.getElementById('createForm').reset();
                }

                function openEditModal(assignmentId) {
                    // Fetch assignment data and populate form
                    fetch(`/admin/form-teacher-assignments/${assignmentId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const assignment = data.formTeacher;
                                document.getElementById('edit_assignment_id').value = assignment.id;
                                document.getElementById('edit_branch_id').value = assignment.branch_id;
                                document.getElementById('edit_branch_name').value = assignment.branch?.name || 'N/A';
                                document.getElementById('edit_teacher_id').value = assignment.user_id;
                                document.getElementById('edit_class_id').value = assignment.school_class_id;
                                document.getElementById('edit_start_date').value = assignment.start_date;
                                document.getElementById('edit_end_date').value = assignment.end_date || '';
                                document.getElementById('edit_notes').value = assignment.notes || '';
                                document.getElementById('edit_is_active').checked = assignment.is_active;
                                document.getElementById('editModal').classList.remove('hidden');
                            } else {
                                alert('Error loading assignment data: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading assignment data');
                        });
                }

                function closeEditModal() {
                    document.getElementById('editModal').classList.add('hidden');
                    document.getElementById('editForm').reset();
                }

                function toggleStatus(assignmentId) {
                    if (confirm('Are you sure you want to change the status of this assignment?')) {
                        fetch(`/admin/form-teacher-assignments/${assignmentId}/toggle-status`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error updating status');
                            });
                    }
                }

                function deleteAssignment(assignmentId) {
                    if (confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) {
                        fetch(`/admin/form-teacher-assignments/${assignmentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error deleting assignment');
                            });
                    }
                }

                // Form submission handlers
                document.getElementById('createForm').addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch('/admin/form-teacher-assignments', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeCreateModal();
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error creating assignment');
                        });
                });

                document.getElementById('editForm').addEventListener('submit', function (e) {
                    e.preventDefault();

                    const assignmentId = document.getElementById('edit_assignment_id').value;
                    const formData = new FormData(this);

                    fetch(`/admin/form-teacher-assignments/${assignmentId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeEditModal();
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error updating assignment');
                        });
                });
            </script>
@endsection

