@extends('layouts.dashboard')

@section('title', 'Subjects')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Subjects</h1>
                    <p class="text-gray-600 mt-2">Manage subjects and their class assignments</p>
                </div>
                <a href="{{ route('subjects.create') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                    <i class="fas fa-plus mr-2"></i>New Subject
                </a>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            @if(auth()->user()->is_super_admin)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Classes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teachers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($subjects as $subject)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                </td>
                                @if(auth()->user()->is_super_admin)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $subject->branch->name ?? 'N/A' }}</div>
                                </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $subject->code ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($subject->classes as $class)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $class->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500">No classes assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $subject->teachers->count() }} teacher(s)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- View Button -->
                                        <button onclick="viewSubject({{ $subject->id }})" 
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors cursor-pointer border border-blue-200"
                                                title="View Details">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </button>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('subjects.edit', $subject->id) }}" 
                                           class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-600 bg-green-50 rounded hover:bg-green-100 transition-colors cursor-pointer border border-green-200"
                                           title="Edit Subject">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button onclick="deleteSubject({{ $subject->id }}, '{{ $subject->name }}')" 
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 bg-red-50 rounded hover:bg-red-100 transition-colors cursor-pointer border border-red-200"
                                                title="Delete Subject">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No subjects found. 
                                    <a href="{{ route('subjects.create') }}" class="text-green-600 hover:text-green-900">Create your first subject</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($subjects instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($subjects, 'links') && $subjects->hasPages())
            <div class="mt-6">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Subject</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete the subject "<span id="subjectName"></span>"? 
                    This action cannot be undone and will remove all class assignments and teacher associations.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 cursor-pointer">
                        Delete
                    </button>
                </form>
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewSubject(subjectId) {
    // For now, redirect to edit page. You can create a separate view page later
    window.location.href = `/subjects/${subjectId}/edit`;
}

function deleteSubject(subjectId, subjectName) {
    document.getElementById('subjectName').textContent = subjectName;
    document.getElementById('deleteForm').action = `/subjects/${subjectId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection



