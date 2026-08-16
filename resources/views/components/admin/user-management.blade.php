@props(['users' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Teachers Management</h2>
        <button id="add-user-btn"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Teacher
        </button>
    </div>

    <!-- User Creation Form (Hidden by default) -->
    <div id="create-user-form" class="mb-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden"
        style="position: relative; z-index: 10;">
        <h3 class="font-bold text-lg mb-4 text-blue-800">Create New Teacher </h3>
        <form id="user-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Full Name</label>
                    <input type="text" id="user-name"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Email</label>
                    <input type="email" id="user-email"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter email" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Role</label>
                    <select id="user-role"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="teacher" selected>Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Branch</label>
                    <select id="user-branch"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="">Select branch</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Password</label>
                    <input type="password" id="user-password"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Enter password (min 6 chars)" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Confirm Password</label>
                    <input type="password" id="user-password-confirm"
                        class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                        placeholder="Confirm password" required>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="createUser()"
                    class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                    Create Teacher
                </button>
                <button type="button" id="cancel-user-btn"
                    class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Users List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branches
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach(($users ?? collect()) as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-1">
                                @foreach($user->branches as $branch)
                                    <span
                                        class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ ucfirst($branch->pivot->role) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                @foreach($user->branches as $branch)
                                    <span
                                        class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded mr-1">{{ $branch->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="inline-flex gap-2">
                                <button onclick="viewTeacherActivities({{ $user->id }})" type="button"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>View</span>
                                </button>
                                <button onclick="editUser({{ $user->id }})" type="button"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-indigo-200 text-indigo-700 hover:bg-indigo-50 hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M4 13v7h7l8.485-8.485a2.5 2.5 0 10-3.536-3.536L7.464 16.464A2 2 0 016 17H4v-2a2 2 0 01.586-1.414l8.95-8.95" />
                                    </svg>
                                    <span>Edit</span>
                                </button>
                                <button onclick="deleteUser({{ $user->id }})" type="button"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-400 cursor-pointer transition-all duration-200 hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2-2h6a2 2 0 012 2v0H5v0a2 2 0 012-2z" />
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

    @if(($users ?? null) instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($users, 'links') && $users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

    <!-- Edit User Modal (Hidden by default) -->
    <div id="edit-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 max-w-2xl mx-auto p-5 border w-8/12 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-blue-800">Edit Teacher</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="edit-user-form" class="space-y-4">
                    <input type="hidden" id="edit-user-id">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Full Name</label>
                        <input type="text" id="edit-user-name"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                            placeholder="Enter full name" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Email</label>
                        <input type="email" id="edit-user-email"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                            placeholder="Enter email" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Role</label>
                        <select id="edit-user-role"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Branch</label>
                        <select id="edit-user-branch"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                            <option value="">Select branch</option>
                            @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">New Password (leave blank to keep
                            current)</label>
                        <input type="password" id="edit-user-password"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                            placeholder="Enter new password (min 6 chars)">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Confirm New Password</label>
                        <input type="password" id="edit-user-password-confirm"
                            class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500"
                            placeholder="Confirm new password">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="updateUser()"
                            class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                            Update Teacher
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // User management functions
        function showCreateUserForm() {
            console.log('showCreateUserForm called - showing form');
            const form = document.getElementById('create-user-form');
            if (form) {
                form.classList.remove('hidden');
                console.log('Form is now visible');
                console.log('Form classes after:', form.className);
                console.log('Form has hidden class:', form.classList.contains('hidden'));
            } else {
                console.error('Form element not found!');
            }
        }

        function hideCreateUserForm() {
            console.log('hideCreateUserForm called - hiding form');
            const form = document.getElementById('create-user-form');
            if (form) {
                form.classList.add('hidden');
                console.log('Form is now hidden');
            } else {
                console.error('Form element not found!');
            }
        }

        function createUser() {
            console.log('createUser function called!');

            // Get form values
            const name = document.getElementById('user-name').value.trim();
            const email = document.getElementById('user-email').value.trim();
            const role = document.getElementById('user-role').value;
            const branchId = document.getElementById('user-branch').value;
            const password = document.getElementById('user-password').value;
            const passwordConfirm = document.getElementById('user-password-confirm').value;

            console.log('Form values:', { name, email, role, branchId, password: password ? '***' : 'empty' });

            // Simple validation
            if (!name || !email || !role || !branchId || !password || !passwordConfirm) {
                alert('All fields are required!');
                return;
            }

            if (password !== passwordConfirm) {
                alert('Passwords do not match!');
                return;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters!');
                return;
            }

            // Create form data
            const formData = {
                name: name,
                email: email,
                role: role,
                branch_id: branchId,
                password: password,
                password_confirmation: passwordConfirm
            };

            console.log('Sending data:', formData);

            // Send request
            fetch('/users', {
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
                        alert('Teacher created successfully! Teacher can now login with these credentials.');
                        document.getElementById('user-form').reset();
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating user. Check console for details.');
                });
        }

        function viewTeacherActivities(userId) {
            console.log('viewTeacherActivities called for user ID:', userId);

            // Redirect to the teacher activities view
            window.location.href = `/super-admin/teacher-activities/${userId}`;
        }

        function editUser(userId) {
            console.log('editUser called for user ID:', userId);

            // Fetch user data and show edit modal
            fetch(`/users/${userId}/edit`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    console.log('User data received:', data);

                    if (data.success && data.user) {
                        // Populate edit form
                        document.getElementById('edit-user-id').value = data.user.id;
                        document.getElementById('edit-user-name').value = data.user.name;
                        document.getElementById('edit-user-email').value = data.user.email;
                        document.getElementById('edit-user-role').value = data.user.branches[0]?.pivot?.role || 'teacher';
                        document.getElementById('edit-user-branch').value = data.user.branches[0]?.id || '';

                        // Clear password fields
                        document.getElementById('edit-user-password').value = '';
                        document.getElementById('edit-user-password-confirm').value = '';

                        // Show modal
                        document.getElementById('edit-user-modal').classList.remove('hidden');
                        console.log('Edit modal should now be visible');
                    } else {
                        alert('Error: ' + (data.message || 'Failed to load teacher data'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                    alert('Error loading user data: ' + error.message);
                });
        }

        function updateUser() {
            console.log('updateUser function called!');

            // Get form values
            const userId = document.getElementById('edit-user-id').value;
            const name = document.getElementById('edit-user-name').value.trim();
            const email = document.getElementById('edit-user-email').value.trim();
            const role = document.getElementById('edit-user-role').value;
            const branchId = document.getElementById('edit-user-branch').value;
            const password = document.getElementById('edit-user-password').value;
            const passwordConfirm = document.getElementById('edit-user-password-confirm').value;

            console.log('Update form values:', { userId, name, email, role, branchId, password: password ? '***' : 'empty' });

            // Basic validation
            if (!name || !email || !role || !branchId) {
                alert('Name, email, role, and branch are required!');
                return;
            }

            // Password validation (if provided)
            if (password && password !== passwordConfirm) {
                alert('Passwords do not match!');
                return;
            }

            if (password && password.length < 6) {
                alert('Password must be at least 6 characters!');
                return;
            }

            // Create form data
            const formData = {
                name: name,
                email: email,
                role: role,
                branch_id: branchId
            };

            // Add password only if provided
            if (password) {
                formData.password = password;
                formData.password_confirmation = passwordConfirm;
            }

            console.log('Sending update data:', formData);

            // Send update request
            fetch(`/users/${userId}`, {
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
                        alert('Teacher updated successfully!');
                        closeEditModal();
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error updating user:', error);
                    alert('Error updating teacher. Check console for details.');
                });
        }

        function closeEditModal() {
            console.log('closeEditModal called');
            const modal = document.getElementById('edit-user-modal');
            if (modal) {
                modal.classList.add('hidden');
                console.log('Edit modal should now be hidden');

                // Reset form
                const editForm = document.getElementById('edit-user-form');
                if (editForm) {
                    editForm.reset();
                }
            }
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this teacher? This action cannot be undone.')) {
                fetch(`/users/${userId}`, {
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
                            showMessage('Teacher deleted successfully!', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            const errorMsg = data.message || 'Unknown error occurred';
                            console.error('Server Error:', errorMsg);
                            showMessage('Error deleting teacher: ' + errorMsg, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete Fetch Error:', error);
                        const errorMsg = 'Network error or server unavailable';
                        showMessage('Error deleting teacher: ' + errorMsg, 'error');
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
            const existingMessage = document.getElementById('user-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            // Create message element
            const messageDiv = document.createElement('div');
            messageDiv.id = 'user-message';
            messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 ${type === 'success'
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

        // Initialize user management component
        document.addEventListener('DOMContentLoaded', function () {
            // Setup Add User button functionality
            const addUserBtn = document.getElementById('add-user-btn');
            if (addUserBtn) {
                console.log('Add User button found and event listener added');
                addUserBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Add User button clicked');
                    const form = document.getElementById('create-user-form');
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
                        console.log('✅ Teacher creation form is now visible!');
                    } else {
                        console.error('Create user form not found!');
                    }
                });
            } else {
                console.error('Add User button not found!');
            }

            // Setup Cancel button functionality
            const cancelUserBtn = document.getElementById('cancel-user-btn');
            if (cancelUserBtn) {
                cancelUserBtn.addEventListener('click', function () {
                    console.log('Cancel button clicked');
                    const form = document.getElementById('create-user-form');
                    if (form) {
                        form.classList.add('hidden');
                        console.log('Form should now be hidden');

                        // Reset form
                        const userForm = document.getElementById('user-form');
                        if (userForm) {
                            userForm.reset();
                        }
                    }
                });
            }

            // Close form when clicking outside
            document.addEventListener('click', function (event) {
                const form = document.getElementById('create-user-form');
                const addBtn = document.getElementById('add-user-btn');

                if (form && !form.contains(event.target) && !addBtn.contains(event.target)) {
                    if (!form.classList.contains('hidden')) {
                        form.classList.add('hidden');
                        console.log('Form closed by clicking outside');
                    }
                }
            });

            // Close edit modal when clicking outside
            document.addEventListener('click', function (event) {
                const modal = document.getElementById('edit-user-modal');
                const modalContent = modal ? modal.querySelector('.relative') : null;

                if (modal && modalContent && !modalContent.contains(event.target)) {
                    if (!modal.classList.contains('hidden')) {
                        closeEditModal();
                        console.log('Edit modal closed by clicking outside');
                    }
                }
            });

            // Close edit modal with Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    const modal = document.getElementById('edit-user-modal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeEditModal();
                        console.log('Edit modal closed with Escape key');
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Form visibility */
        #create-user-form {
            transition: all 0.3s ease-in-out;
            transform-origin: top;
        }

        #create-user-form.hidden {
            display: none;
            opacity: 0;
            transform: translateY(-10px) scale(0.98);
        }

        #create-user-form:not(.hidden) {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Edit modal styling */
        #edit-user-modal {
            transition: all 0.3s ease-in-out;
        }

        #edit-user-modal:not(.hidden) {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #edit-user-modal .relative {
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
