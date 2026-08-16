@extends('layouts.dashboard')

@section('title', 'Branches')

@section('dashboard')
<div class="max-w-7xl mx-auto p-6">
    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Branch Management</h1>
        <button onclick="showCreateBranchForm()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Branch
        </button>
    </div>

    <!-- Branch Creation Form (Hidden by default) -->
    <div id="create-branch-form" class="hidden mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
        <h3 class="font-medium mb-4">Create New Branch</h3>
        <form id="branch-form" method="POST" action="{{ route('branches.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Branch Name</label>
                    <input type="text" name="name" id="branch-name" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Enter branch name" required>
                    @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="+234...">
                    @error('phone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <input type="text" name="address" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Street address">
                    @error('address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">City</label>
                    <input type="text" name="city" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">State</label>
                    <input type="text" name="state" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    @error('state')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Country</label>
                    <input type="text" name="country" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Nigeria">
                    @error('country')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    Create Branch
                </button>
                <button type="button" onclick="hideCreateBranchForm()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Branches List -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\Branch::withCount(['classes', 'users'])->get() as $branch)
                    @php
                        $pendingAdmissions = \App\Models\AdmissionApplication::where('branch_id', $branch->id)->where('status', 'pending')->count();
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $branch->name }}</div>
                            @if($pendingAdmissions > 0)
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $pendingAdmissions }} pending admission{{ $pendingAdmissions > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $branch->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $branch->classes_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $branch->users_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $totalAdmissions = \App\Models\AdmissionApplication::where('branch_id', $branch->id)->count();
                            @endphp
                            <div class="text-sm text-gray-500">{{ $totalAdmissions }}</div>
                            @if($pendingAdmissions > 0)
                                <div class="text-xs text-yellow-600">{{ $pendingAdmissions }} pending</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewBranch({{ $branch->id }})" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button onclick="editBranch({{ $branch->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button onclick="deleteBranch({{ $branch->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showCreateBranchForm() {
    document.getElementById('create-branch-form').classList.remove('hidden');
    document.getElementById('branch-name').focus();
}

function hideCreateBranchForm() {
    document.getElementById('create-branch-form').classList.add('hidden');
    document.getElementById('branch-form').reset();
}

function viewBranch(id) {
    // Redirect to branch detail page
    window.location.href = '/branches/' + id;
}

function editBranch(id) {
    window.location.href = '/branches/' + id + '/edit';
}

function deleteBranch(id) {
    if (confirm('Are you sure you want to delete this branch?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/branches/' + id;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection



