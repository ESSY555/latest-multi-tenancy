@extends('layouts.dashboard')

@section('title', 'Teachers')

@section('dashboard')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">User Management</h1>
        <button onclick="showCreateUserForm()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New User
        </button>
    </div>

    {{-- User Creation Form (Hidden by default) - COMMENTED OUT TO AVOID CONFLICTS --}}
    {{-- <div id="create-user-form" class="hidden mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
        <h3 class="font-medium mb-4">Create New User</h3>
        <form id="user-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name</label>
                    <input type="text" id="user-name" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Enter full name">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" id="user-email" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Enter email">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select id="user-role" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="parent">Parent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Branch</label>
                    <select id="user-branch" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Select branch</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    Create User
                </button>
                <button type="button" onclick="hideCreateUserForm()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div> --}}

    <!-- Users List -->
    <div class="bg-white rounded-lg shadow p-6">
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\User::with('branches')->latest()->get() as $user)
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
                            <div class="inline-flex gap-2">
                                <button onclick="editUser({{ $user->id }})" type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-indigo-200 text-indigo-700 hover:bg-indigo-50 hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 13v7h7l8.485-8.485a2.5 2.5 0 10-3.536-3.536L7.464 16.464A2 2 0 016 17H4v-2a2 2 0 01.586-1.414l8.95-8.95" />
                                    </svg>
                                    <span>Edit</span>
                                </button>
                                <button onclick="deleteUser({{ $user->id }})" type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-400">
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
    </div>
</div>

<script>
function showCreateUserForm() {
    document.getElementById('create-user-form').classList.remove('hidden');
}

function hideCreateUserForm() {
    document.getElementById('create-user-form').classList.add('hidden');
}

function editUser(id) {
    // Implement edit functionality
    console.log('Edit user:', id);
}

function deleteUser(id) {
    // Implement delete functionality
    if (confirm('Are you sure you want to delete this user?')) {
        console.log('Delete user:', id);
    }
}

// Handle form submission
document.getElementById('user-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implement form submission logic
    console.log('Form submitted');
});
</script>
@endsection



