@extends('layouts.dashboard')

@section('title', 'Class Result Management')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Class Result Management</h1>
                    <p class="text-gray-600 mt-2">Manage results and add comments for your assigned class</p>
                    @if($currentAcademicYear)
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>Current Academic Year: <strong>{{ $currentAcademicYear->name }}</strong>
                        </p>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('result.create') }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                        <i class="fas fa-plus mr-2"></i>Add Result
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Teacher Info Card -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">Form Teacher</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Your Role:</strong> Add comments to student results and forward them for admin approval. 
                        You can also enter results for subjects you teach.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Results</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-comment-alt text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">With Comments</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->whereNotNull('form_teacher_comment')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Comments</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->whereNull('form_teacher_comment')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $results->where('is_approved', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select name="class_id" id="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select name="subject_id" id="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="term_id" class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                        <select name="term_id" id="term_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Terms</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }} - {{ $term->academicYear->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="no-comment" {{ request('status') == 'no-comment' ? 'selected' : '' }}>No Comment</option>
                        </select>
                    </div>

                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('result.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Class Results ({{ $results->total() }} total)</h3>
            </div>

            @if($results->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $result)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-green-800">
                                                        {{ substr($result->student->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $result->student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $result->student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->schoolClass->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->academicTerm->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $result->total }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($result->grade === 'A') bg-green-100 text-green-800
                                            @elseif($result->grade === 'B') bg-blue-100 text-blue-800
                                            @elseif($result->grade === 'C') bg-yellow-100 text-yellow-800
                                            @elseif($result->grade === 'D') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $result->grade }} - {{ $result->remark }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result->position ? $result->position . ((($result->position % 100) >= 11 && ($result->position % 100) <= 13) ? 'th' : (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$result->position % 10])) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($result->form_teacher_comment)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Added
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($result->is_approved)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('result.student-sheet', ['studentId' => $result->student_id, 'termId' => $result->term_id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$result->form_teacher_comment)
                                                <button onclick="addComment({{ $result->id }})" 
                                                        class="text-purple-600 hover:text-purple-900 cursor-pointer">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $results->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
                        <p class="text-gray-500">No results match your current filters.</p>
                        <a href="{{ route('result.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                            <i class="fas fa-plus mr-2"></i>Add First Result
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Teacher Instructions -->
        <div class="mt-6 bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-purple-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800">Form Teacher Instructions</h3>
                    <div class="mt-2 text-sm text-purple-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Add Comments:</strong> Click the comment icon to add form teacher comments to student results</li>
                            <li><strong>Review Results:</strong> Check all results for your assigned class before forwarding to admin</li>
                            <li><strong>Enter Results:</strong> You can also enter results for subjects you teach</li>
                            <li><strong>Approval Process:</strong> Results with comments are forwarded to admin for final approval</li>
                            <li><strong>Student Access:</strong> Only approved results are visible to students and parents</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comment Modal -->
<div id="commentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Form Teacher Comment</h3>
            <form id="commentForm">
                @csrf
                <input type="hidden" id="resultId" name="result_id">
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                    <textarea id="comment" name="form_teacher_comment" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                              placeholder="Enter your comment about the student's performance..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCommentModal()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors cursor-pointer">
                        Add Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addComment(resultId) {
    document.getElementById('resultId').value = resultId;
    document.getElementById('commentModal').classList.remove('hidden');
}

function closeCommentModal() {
    document.getElementById('commentModal').classList.add('hidden');
    document.getElementById('commentForm').reset();
}

document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const resultId = formData.get('result_id');
    
    fetch(`/result/${resultId}/comment`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding comment: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding comment');
    });
});
</script>
@endsection

