@extends('layouts.dashboard')

@section('title', 'User Management')

@section('dashboard')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-6 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage system access for admins, teachers, students, and parents.</p>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all transform active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New User
            </button>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User Information</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role & Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\User::with('branches')->latest()->get() as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold select-none">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                @forelse($user->branches as $branch)
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700 rounded-full">
                                            {{ $branch->pivot->role }}
                                        </span>
                                        <span class="text-xs text-gray-600 font-medium">{{ $branch->name }}</span>
                                    </div>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No branch assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ $user->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2-2h6a2 2 0 012 2v0H5v0a2 2 0 012-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Create/Edit User -->
<div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Add New User</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="userForm" onsubmit="saveUser(event)" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="userId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Full Name</label>
                    <input type="text" id="userName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" placeholder="Enter full name">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email Address</label>
                    <input type="email" id="userEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" placeholder="name@school.com">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Role</label>
                        <select id="userRole" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="admin">Admin</option>
                            <option value="parent">Parent</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Branch</label>
                        <select id="userBranch" name="branch_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="passwordField">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                    <input type="password" id="userPassword" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" placeholder="••••••••">
                    <p id="passwordHint" class="text-[10px] text-gray-400 mt-1 hidden">Leave blank to keep current password</p>
                </div>

                <div id="passwordConfirmationField" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Confirm Password</label>
                    <input type="password" id="userPasswordConfirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" placeholder="••••••••">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" id="saveButton" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 shadow-md transition-all active:scale-95">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center transform transition-all">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Delete User</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete <span id="deleteUserName" class="font-bold text-gray-900"></span>? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="confirmDeleteButton" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 shadow-md transition-all active:scale-95">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('userModal');
const deleteModal = document.getElementById('deleteModal');
const userForm = document.getElementById('userForm');
const modalTitle = document.getElementById('modalTitle');
const saveButton = document.getElementById('saveButton');
const methodInput = document.getElementById('formMethod');
const pwHint = document.getElementById('passwordHint');
const pwConfirmField = document.getElementById('passwordConfirmationField');

function openCreateModal() {
    userForm.reset();
    document.getElementById('userId').value = '';
    methodInput.value = 'POST';
    modalTitle.textContent = 'Add New User';
    saveButton.textContent = 'Create User';
    pwHint.classList.add('hidden');
    pwConfirmField.classList.add('hidden');
    document.getElementById('userPassword').required = true;
    modal.classList.remove('hidden');
}

function openEditModal(id) {
    userForm.reset();
    modalTitle.textContent = 'Edit User';
    saveButton.textContent = 'Update User';
    pwHint.classList.remove('hidden');
    pwConfirmField.classList.remove('hidden');
    document.getElementById('userPassword').required = false;
    
    fetch(`/users/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('userId').value = data.user.id;
                document.getElementById('userName').value = data.user.name;
                document.getElementById('userEmail').value = data.user.email;
                document.getElementById('userRole').value = data.user.branches[0]?.pivot?.role || 'student';
                document.getElementById('userBranch').value = data.user.branches[0]?.id || '';
                methodInput.value = 'PUT';
                modal.classList.remove('hidden');
            } else {
                alert(data.message || 'Error loading user data');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to fetch user details');
        });
}

function closeModal() {
    modal.classList.add('hidden');
}

async function saveUser(e) {
    e.preventDefault();
    const id = document.getElementById('userId').value;
    const url = id ? `/users/${id}` : '/users';
    const formData = new FormData(userForm);
    
    // Add spoofed method for PUT
    if (id) formData.set('_method', 'PUT');

    try {
        const res = await fetch(url, {
            method: 'POST', // Always POST, _method handles it in Laravel
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error saving user');
        }
    } catch (err) {
        console.error(err);
        alert('An unexpected error occurred');
    }
}

function confirmDelete(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('confirmDeleteButton').onclick = async () => {
        try {
            const res = await fetch(`/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error deleting user');
            }
        } catch (err) {
            console.error(err);
            alert('Failed to delete user');
        }
    };
    deleteModal.classList.remove('hidden');
}

function closeDeleteModal() {
    deleteModal.classList.add('hidden');
}
</script>
@endsection

