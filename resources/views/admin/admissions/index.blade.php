@extends('layouts.dashboard')

@section('title', 'Admission Applications - Admin')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admission Applications</h1>
            <p class="text-gray-600 mt-2">Manage and review student admission applications</p>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <!-- Search and Filters -->
                <div class="flex-1 w-full">
                    <form method="GET" action="{{ route('admin.admissions.index') }}" class="flex flex-col md:flex-row flex-wrap xl:flex-nowrap gap-3 items-center w-full">
                        <div class="w-full md:flex-1">
                            <input type="text" name="search" value="{{ $search }}" 
                                   placeholder="Search by name, email, phone..." 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="w-full md:flex-1">
                            <select name="status" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <!-- Branch Filter (Super Admin Only) -->
                        @if($branches)
                        <div class="w-full md:flex-1">
                            <select name="branch" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Branches</option>
                                @foreach($branches as $branchOption)
                                <option value="{{ $branchOption->id }}" {{ $branch == $branchOption->id ? 'selected' : '' }}>
                                    {{ $branchOption->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <!-- Date Filters -->
                        <div class="w-full md:flex-1 lg:flex-[1.5] flex flex-col lg:flex-row gap-2">
                            <input type="date" name="date_from" value="{{ $dateFrom }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="date" name="date_to" value="{{ $dateTo }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="w-full md:w-auto flex gap-2">
                            <button type="submit" class="flex-1 md:flex-none px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                Filter
                            </button>
                            
                            <a href="{{ route('admin.admissions.index') }}" class="flex-1 md:flex-none px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition-colors text-center">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Export Button -->
                <div class="w-full lg:w-auto flex justify-start lg:justify-end mt-2 lg:mt-0">
                    <a href="{{ route('admin.admissions.export-csv') }}?{{ http_build_query(request()->query()) }}" 
                       class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors whitespace-nowrap">
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Reviewed</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['reviewed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white p-4 rounded-lg shadow mb-6" id="bulk-actions" style="display: none;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        <span id="selected-count">0</span> applications selected
                    </span>
                    
                    <select id="bulk-status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    
                    <textarea id="bulk-notes" rows="2" placeholder="Admin notes (optional)" 
                              class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    
                    <button onclick="bulkUpdateStatus()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update Selected
                    </button>
                </div>
                
                <button onclick="clearSelection()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Applications</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            @if(auth()->user()->hasRole('super-admin'))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="application-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="{{ $application->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $application->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->age }} years old</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                <div>
                                    <div class="text-sm text-gray-900">{{ $application->primary_contact_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                <span class="text-sm text-gray-900">{{ $application->current_grade ?: 'N/A' }}</span>
                            </td>
                            @if(auth()->user()->hasRole('super-admin'))
                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                <span class="text-sm text-gray-900">{{ $application->branch->name ?? 'N/A' }}</span>
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $application->status_color }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 cursor-pointer" onclick="viewAdmission({{ $application->id }})">
                                {{ $application->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewAdmission({{ $application->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </button>
                                <button onclick="deleteApplication({{ $application->id }})" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No applications found matching your criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Admission Detail Modal -->
<div id="admission-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 w-11/12 max-w-2xl shadow-2xl rounded-2xl bg-white border border-gray-200">
        <div class="relative">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Admission Application Details</h3>
                </div>
                <button onclick="hideAdmissionModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Admission Details -->
            <div class="space-y-6">
                <!-- Student Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Student Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium ml-2" id="admission-name"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Date of Birth:</span>
                            <span class="font-medium ml-2" id="admission-dob"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Gender:</span>
                            <span class="font-medium ml-2" id="admission-gender"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Current Grade:</span>
                            <span class="font-medium ml-2" id="admission-grade"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium ml-2" id="admission-status"></span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Contact Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Primary Contact:</span>
                            <span class="font-medium ml-2" id="admission-contact"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Relationship:</span>
                            <span class="font-medium ml-2" id="admission-relationship"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium ml-2" id="admission-phone"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium ml-2" id="admission-email"></span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Additional Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Branch:</span>
                            <span class="font-medium ml-2" id="admission-branch"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">How They Heard:</span>
                            <span class="font-medium ml-2" id="admission-hear-about"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Address:</span>
                            <span class="font-medium ml-2" id="admission-address"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Additional Info:</span>
                            <span class="font-medium ml-2" id="admission-additional"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Submitted On:</span>
                            <span class="font-medium ml-2" id="admission-created"></span>
                        </div>
                    </div>
                </div>

                <!-- Status Update -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Update Status</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="admission-status-select" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending">Pending</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea id="admin-notes" rows="3" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add notes about this application..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Hidden input for admission ID -->
                    <input type="hidden" id="admission-id">
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-4">
                        <button onclick="hideAdmissionModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Cancel
                        </button>
                        <button onclick="updateAdmissionStatus()" id="update-status-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentApplicationId = null;
let selectedApplications = new Set();

// Checkbox management
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.application-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        if (this.checked) {
            selectedApplications.add(checkbox.value);
        } else {
            selectedApplications.delete(checkbox.value);
        }
    });
    updateBulkActions();
});

document.querySelectorAll('.application-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            selectedApplications.add(this.value);
        } else {
            selectedApplications.delete(this.value);
        }
        updateBulkActions();
        
        // Update select-all checkbox
        const allCheckboxes = document.querySelectorAll('.application-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.application-checkbox:checked');
        document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
    });
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (selectedApplications.size > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedApplications.size;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    selectedApplications.clear();
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.application-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

// Bulk update status
function bulkUpdateStatus() {
    const status = document.getElementById('bulk-status').value;
    const notes = document.getElementById('bulk-notes').value;
    
    if (!status) {
        showToast('Please select a status', 'error');
        return;
    }
    
    if (selectedApplications.size === 0) {
        showToast('Please select applications to update', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to update ${selectedApplications.size} applications to "${status}"?`)) {
        return;
    }
    
    fetch('{{ route("admin.admissions.bulk-update-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            application_ids: Array.from(selectedApplications),
            status: status,
            admin_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            clearSelection();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Error updating applications: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating applications', 'error');
    });
}

// Admission management functions
function viewAdmission(admissionId) {
    // Fetch admission details and show modal
    fetch(`/admin/admissions/${admissionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAdmissionModal(data.admission);
            } else {
                showToast('Error loading admission details', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading admission details', 'error');
        });
}

function showAdmissionModal(admission) {
    // Populate modal with admission data
    document.getElementById('admission-name').textContent = admission.full_name;
    document.getElementById('admission-email').textContent = admission.email;
    document.getElementById('admission-phone').textContent = admission.phone_number;
    document.getElementById('admission-branch').textContent = admission.branch?.name || 'N/A';
    document.getElementById('admission-grade').textContent = admission.current_grade || 'N/A';
    document.getElementById('admission-status').textContent = admission.status;
    document.getElementById('admission-status').className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${admission.status_color}`;
    document.getElementById('admission-dob').textContent = admission.date_of_birth ? new Date(admission.date_of_birth).toLocaleDateString() : 'N/A';
    document.getElementById('admission-gender').textContent = admission.gender;
    document.getElementById('admission-contact').textContent = admission.primary_contact_name;
    document.getElementById('admission-relationship').textContent = admission.relationship;
    document.getElementById('admission-address').textContent = admission.address;
    document.getElementById('admission-hear-about').textContent = admission.hear_about_school || 'N/A';
    document.getElementById('admission-additional').textContent = admission.additional_info || 'N/A';
    document.getElementById('admission-created').textContent = admission.created_at ? new Date(admission.created_at).toLocaleDateString() : 'N/A';
    
    // Set current status for form
    document.getElementById('admission-status-select').value = admission.status;
    document.getElementById('admin-notes').value = admission.admin_notes || '';
    document.getElementById('admission-id').value = admission.id;
    
    // Show modal
    document.getElementById('admission-modal').classList.remove('hidden');
}

function hideAdmissionModal() {
    document.getElementById('admission-modal').classList.add('hidden');
}

function updateAdmissionStatus() {
    const admissionId = document.getElementById('admission-id').value;
    const status = document.getElementById('admission-status-select').value;
    const notes = document.getElementById('admin-notes').value;
    
    const updateBtn = document.getElementById('update-status-btn');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    updateBtn.classList.add('opacity-75', 'cursor-not-allowed');
    updateBtn.classList.remove('hover:-translate-y-0.5', 'hover:shadow-xl');

    fetch(`/admin/admissions/${admissionId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status, admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Admission status updated successfully!', 'success');
            hideAdmissionModal();
            // Refresh the page to show updated data
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Error updating admission status: ' + data.message, 'error');
            resetUpdateBtn();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating admission status', 'error');
        resetUpdateBtn();
    });

    function resetUpdateBtn() {
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
        updateBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        updateBtn.classList.add('hover:-translate-y-0.5', 'hover:shadow-xl');
    }
}

function deleteApplication(applicationId) {
    if (!confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/admin/admissions/${applicationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (response.ok) {
            showToast('Application deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Error deleting application', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error deleting application', 'error');
    });
}
</script>
@endpush
@endsection

