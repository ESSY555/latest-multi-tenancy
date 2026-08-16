@extends('layouts.dashboard')

@section('title', $branch->name . ' - Branch Details')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $branch->name }}</h1>
            <p class="text-gray-600">{{ $branch->code }} • {{ $branch->address }}, {{ $branch->city }}</p>
        </div>
        <a href="{{ route('branches.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
            ← Back to Branches
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Classes</div>
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['classes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Teachers</div>
            <div class="text-2xl font-semibold text-green-600">{{ $stats['teachers'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Students</div>
            <div class="text-2xl font-semibold text-purple-600">{{ $stats['students'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Parents</div>
            <div class="text-2xl font-semibold text-indigo-600">{{ $stats['parents'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Total Admissions</div>
            <div class="text-2xl font-semibold text-orange-600">{{ $stats['admissions'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Pending Admissions</div>
            <div class="text-2xl font-semibold text-yellow-600">{{ $stats['pending_admissions'] }}</div>
        </div>
    </div>

    <!-- Recent Activities Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Classes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Classes</h2>
                    <a href="{{ route('class-management') }}" class="text-blue-600 text-sm hover:text-blue-800">View all</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentClasses->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentClasses as $class)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $class->grade_level }} • {{ $class->academic_year }}</div>
                                </div>
                                <div class="text-sm text-gray-600">{{ $class->enrollments()->count() }} students</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No classes found in this branch.</p>
                @endif
            </div>
        </div>

        <!-- Recent Admissions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Admissions</h2>
                    <a href="{{ route('admin.admissions.index') }}" class="text-blue-600 text-sm hover:text-blue-800">View all</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentAdmissions->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAdmissions as $admission)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="viewAdmission({{ $admission->id }})">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $admission->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $admission->email }}</div>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $admission->status_color }}">
                                    {{ ucfirst($admission->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No admission applications found.</p>
                @endif
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
            </div>
            <div class="p-6">
                @if($recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                                <span class="text-sm text-gray-600 capitalize">{{ $user->pivot->role ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No users found in this branch.</p>
                @endif
            </div>
        </div>

        <!-- Recent Results -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Results</h2>
            </div>
            <div class="p-6">
                @if($recentResults->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentResults as $result)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $result->student->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $result->schoolClass->name ?? 'N/A' }}</div>
                                </div>
                                <div class="text-sm text-gray-600">{{ $result->total ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No results found in this branch.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.admissions.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-blue-900">View Admissions</div>
                    <div class="text-sm text-blue-600">{{ $stats['pending_admissions'] }} pending</div>
                </div>
            </a>

            <a href="{{ route('class-management') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-green-900">Manage Classes</div>
                    <div class="text-sm text-green-600">{{ $stats['classes'] }} classes</div>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-purple-900">Manage Users</div>
                    <div class="text-sm text-purple-600">{{ $stats['teachers'] + $stats['students'] + $stats['parents'] }} total</div>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-orange-900">View Reports</div>
                    <div class="text-sm text-orange-600">Analytics & insights</div>
                </div>
            </a>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Student Information -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Student Information
                    </h4>
                    <div class="space-y-4">
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Full Name</label>
                            <p id="admission-name" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                            <p id="admission-email" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone</label>
                            <p id="admission-phone" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Date of Birth</label>
                            <p id="admission-dob" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Gender</label>
                            <p id="admission-gender" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Grade</label>
                            <p id="admission-grade" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Contact & Additional Information -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact & Additional Info
                    </h4>
                    <div class="space-y-4">
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Primary Contact</label>
                            <p id="admission-contact" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Relationship</label>
                            <p id="admission-relationship" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Address</label>
                            <p id="admission-address" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Branch</label>
                            <p id="admission-branch" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">How did you hear about us?</label>
                            <p id="admission-hear-about" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-8">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Additional Information
                    </h4>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Additional Notes</label>
                        <p id="admission-additional" class="text-gray-900 font-medium mt-1"></p>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="mb-8">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h4 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Application Status
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Status</label>
                            <p id="admission-status" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1"></p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted On</label>
                            <p id="admission-created" class="text-gray-900 font-medium mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Update Form -->
            <div class="border-t pt-8">
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <h4 class="font-bold text-blue-900 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Update Application Status
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="admission-status-select" class="block text-sm font-semibold text-blue-700 mb-2">New Status</label>
                            <select id="admission-status-select" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="pending">⏳ Pending</option>
                                <option value="reviewed">👀 Reviewed</option>
                                <option value="approved">✅ Approved</option>
                                <option value="rejected">❌ Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label for="admin-notes" class="block text-sm font-semibold text-blue-700 mb-2">Admin Notes</label>
                            <textarea id="admin-notes" rows="3" class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Add notes about this application..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Hidden input for admission ID -->
                    <input type="hidden" id="admission-id">
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button onclick="hideAdmissionModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Cancel
                        </button>
                        <button onclick="updateAdmissionStatus()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Admission management functions
function viewAdmission(admissionId) {
    // Fetch admission details and show modal
    fetch(`/admin/admissions/${admissionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAdmissionModal(data.admission);
            } else {
                alert('Error loading admission details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading admission details');
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
    document.getElementById('admission-dob').textContent = admission.date_of_birth;
    document.getElementById('admission-gender').textContent = admission.gender;
    document.getElementById('admission-contact').textContent = admission.primary_contact_name;
    document.getElementById('admission-relationship').textContent = admission.relationship;
    document.getElementById('admission-address').textContent = admission.address;
    document.getElementById('admission-hear-about').textContent = admission.hear_about_school || 'N/A';
    document.getElementById('admission-additional').textContent = admission.additional_info || 'N/A';
    document.getElementById('admission-created').textContent = admission.created_at;
    
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
    
    fetch(`/admin/admissions/${admissionId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status, admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Admission status updated successfully!');
            hideAdmissionModal();
            // Refresh the page to show updated data
            window.location.reload();
        } else {
            alert('Error updating admission status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating admission status');
    });
}
</script>
@endsection

