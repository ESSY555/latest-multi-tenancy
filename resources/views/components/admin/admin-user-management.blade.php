@props(['users' => null])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">User Management</h2>
        <button id="add-user-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New User
        </button>
    </div>

    <!-- User Creation Form (Hidden by default) -->
    <div id="create-user-form" class="mb-6 p-6 border-2 border-blue-300 rounded-lg bg-blue-50 hidden" style="position: relative; z-index: 10;">
        <h3 class="font-bold text-lg mb-4 text-blue-800">Create New User</h3>
        <form id="user-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Full Name</label>
                    <input type="text" id="user-name" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Email</label>
                    <input type="email" id="user-email" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Enter email" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Role</label>
                    <select id="user-role" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="">Select role</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Branch</label>
                    <select id="user-branch" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" required>
                        <option value="">Select branch</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                            <option value="{{ $b->id }}" {{ session('current_branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Password</label>
                    <input type="password" id="user-password" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Enter password (min 6 chars)" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700">Confirm Password</label>
                    <input type="password" id="user-password-confirm" class="w-full border-2 border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" placeholder="Confirm password" required>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="createUser()" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors cursor-pointer shadow-lg">
                    Create User
                </button>
                <button type="button" id="cancel-user-btn" class="px-6 py-3 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors cursor-pointer shadow-lg">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <form action="{{ route('admin-user-management') }}" method="GET" class="flex flex-wrap gap-4 items-center" id="filter-form">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Role:</label>
                <select name="role" id="role-filter" class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500" onchange="document.getElementById('filter-form').submit()">
                    <option value="">All Roles</option>
                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search:</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" id="search-input" placeholder="Search by name or email..." class="border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Search</button>
                    @if(request('role') || request('search'))
                        <a href="{{ route('admin-user-management') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Clear</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Users List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branches</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
                @foreach(($users ?? collect()) as $user)
                @php
                    $roles = $user->branches->pluck('pivot.role')->filter()->unique()->toArray();
                    if ($user->is_super_admin) {
                        $roles[] = 'admin';
                    }
                    $rolesString = strtolower(implode(' ', $roles));
                @endphp
                <tr class="user-row" data-role="{{ $rolesString }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex gap-1">
                            @foreach($user->branches as $branch)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ ucfirst($branch->pivot->role) }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            @foreach($user->branches as $branch)
                                <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded mr-1">{{ $branch->name }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
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
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit User</h3>
            <form id="edit-user-form" class="space-y-4">
                <input type="hidden" id="edit-user-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="edit-user-name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="edit-user-email" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="edit-user-role" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-blue-500">
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="button" onclick="updateUser()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide create user form
    const addUserBtn = document.getElementById('add-user-btn');
    const createUserForm = document.getElementById('create-user-form');
    const cancelUserBtn = document.getElementById('cancel-user-btn');

    if (addUserBtn && createUserForm) {
        addUserBtn.addEventListener('click', function() {
            createUserForm.classList.remove('hidden');
            console.log('Form should now be visible');
        });
    }

    if (cancelUserBtn && createUserForm) {
        cancelUserBtn.addEventListener('click', function() {
            createUserForm.classList.add('hidden');
            console.log('Form should now be hidden');
            
            // Reset form
            const userForm = document.getElementById('user-form');
            if (userForm) {
                userForm.reset();
            }
        });
    }



    // Close form when clicking outside
    document.addEventListener('click', function(event) {
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
    document.addEventListener('click', function(event) {
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
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('edit-user-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeEditModal();
                console.log('Edit modal closed with Escape key');
            }
        }
    });
});

function createUser() {
    const name = document.getElementById('user-name').value;
    const email = document.getElementById('user-email').value;
    const role = document.getElementById('user-role').value;
    const branch = document.getElementById('user-branch').value;
    const password = document.getElementById('user-password').value;
    const passwordConfirm = document.getElementById('user-password-confirm').value;

    if (!name || !email || !role || !branch || !password || !passwordConfirm) {
        alert('Please fill in all fields');
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

    // Send AJAX request to create user
    fetch('/admin/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            email: email,
            role: role,
            branch_id: branch,
            password: password,
            password_confirmation: passwordConfirm
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the user');
    });
}

function editUser(userId) {
    // Fetch user data and populate modal
    fetch(`/admin/users/${userId}/edit`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('edit-user-id').value = data.user.id;
        document.getElementById('edit-user-name').value = data.user.name;
        document.getElementById('edit-user-email').value = data.user.email;
        document.getElementById('edit-user-role').value = data.user.role;
        
        document.getElementById('edit-user-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching user data');
    });
}

function updateUser() {
    const userId = document.getElementById('edit-user-id').value;
    const name = document.getElementById('edit-user-name').value;
    const email = document.getElementById('edit-user-email').value;
    const role = document.getElementById('edit-user-role').value;

    if (!name || !email || !role) {
        alert('Please fill in all fields');
        return;
    }

    fetch(`/admin/users/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            email: email,
            role: role
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the user');
    });
}

function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }

    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User deleted successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the user');
    });
}

function closeEditModal() {
    document.getElementById('edit-user-modal').classList.add('hidden');
}
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

