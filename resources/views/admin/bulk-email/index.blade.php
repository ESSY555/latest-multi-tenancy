@extends('layouts.dashboard')

@section('title', 'Bulk Email to Parents')

@section('dashboard')
<!-- Header Section -->
<div class="bg-white shadow-lg border-b border-gray-200 mb-6">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-3 rounded-xl shadow-lg">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bulk Email to Parents</h1>
                    <p class="text-gray-600 mt-1">Send personalized communications using students' login email addresses</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i>System Ready
                </div>
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Current Branch Recipients</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $parentStats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Student account emails in current branch
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">All Branches Recipients</p>
                        <p class="text-3xl font-bold text-green-600">{{ $allBranchesStats['valid_emails'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-globe text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <i class="fas fa-check-circle mr-1"></i>
                    Valid student account emails across all branches
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Branches</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $branches->count() }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="fas fa-building text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <i class="fas fa-sitemap mr-1"></i>
                    Available branches
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form id="bulkEmailForm" method="POST" action="{{ route('bulk-email.send') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Branch Selection Section -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-building mr-3"></i>
                                Select Branches
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectBranch('current')">
                                    <input class="form-radio text-purple-600 focus:ring-purple-500" type="radio" name="branch_selection" id="branch_current" value="current" checked>
                                    <div class="flex-1">
                                        <label for="branch_current" class="text-lg font-medium text-gray-900 cursor-pointer">Current Branch Only</label>
                                        <p class="text-sm text-gray-600 mt-1">Send to parents in the currently selected branch</p>
                                    </div>
                                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $parentStats['valid_emails'] }} parents
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectBranch('all')">
                                    <input class="form-radio text-purple-600 focus:ring-purple-500" type="radio" name="branch_selection" id="branch_all" value="all">
                                    <div class="flex-1">
                                        <label for="branch_all" class="text-lg font-medium text-gray-900 cursor-pointer">All Branches</label>
                                        <p class="text-sm text-gray-600 mt-1">Send to parents across all branches</p>
                                    </div>
                                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $allBranchesStats['valid_emails'] }} parents
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectBranch('specific')">
                                    <input class="form-radio text-purple-600 focus:ring-purple-500" type="radio" name="branch_selection" id="branch_specific" value="specific">
                                    <div class="flex-1">
                                        <label for="branch_specific" class="text-lg font-medium text-gray-900 cursor-pointer">Specific Branches</label>
                                        <p class="text-sm text-gray-600 mt-1">Select specific branches to target</p>
                                    </div>
                                    <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $branches->count() }} branches
                                    </div>
                                </div>
                            </div>

                            <!-- Specific Branch Selection -->
                            <div id="branch-selection" class="mt-6 p-4 bg-purple-50 rounded-xl border border-purple-200" style="display: none;">
                                <h4 class="text-lg font-semibold text-purple-900 mb-4">Select Branches</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($branches as $branch)
                                        <label class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-purple-200 hover:bg-purple-50 cursor-pointer transition-colors">
                                            <input class="form-checkbox text-purple-600 focus:ring-purple-500" type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" id="branch_{{ $branch->id }}">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $branch->name }}</span>
                                                <span class="text-sm text-gray-600 ml-2">({{ $branch->location ?? 'No location' }})</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Targeting Section -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-bullseye mr-3"></i>
                                Target Recipients
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectTarget('all')">
                                    <input class="form-radio text-indigo-600 focus:ring-indigo-500" type="radio" name="target_type" id="target_all" value="all" checked>
                                    <div class="flex-1">
                                        <label for="target_all" class="text-lg font-medium text-gray-900 cursor-pointer">All Student Account Emails in Current Branch</label>
                                        <p class="text-sm text-gray-600 mt-1">Send to all valid student login emails (used by parents)</p>
                                    </div>
                                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $parentStats['valid_emails'] }} parents
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectTarget('class')">
                                    <input class="form-radio text-indigo-600 focus:ring-indigo-500" type="radio" name="target_type" id="target_class" value="class">
                                    <div class="flex-1">
                                        <label for="target_class" class="text-lg font-medium text-gray-900 cursor-pointer">Student Account Emails of Specific Classes</label>
                                        <p class="text-sm text-gray-600 mt-1">Target valid student login emails in selected classes</p>
                                    </div>
                                    <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $classes->count() }} classes
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="selectTarget('specific')">
                                    <input class="form-radio text-indigo-600 focus:ring-indigo-500" type="radio" name="target_type" id="target_specific" value="specific">
                                    <div class="flex-1">
                                        <label for="target_specific" class="text-lg font-medium text-gray-900 cursor-pointer">Specific Email Addresses</label>
                                        <p class="text-sm text-gray-600 mt-1">Enter specific email addresses manually</p>
                                    </div>
                                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Custom
                                    </div>
                                </div>
                            </div>

                            <!-- Class Selection -->
                            <div id="class-selection" class="mt-6 p-4 bg-purple-50 rounded-xl border border-purple-200" style="display: none;">
                                <h4 class="text-lg font-semibold text-purple-900 mb-4">Select Classes</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($classes as $class)
                                        <label class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-purple-200 hover:bg-purple-50 cursor-pointer transition-colors">
                                            <input class="form-checkbox text-purple-600 focus:ring-purple-500" type="checkbox" name="class_ids[]" value="{{ $class->id }}" id="class_{{ $class->id }}">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $class->name }}</span>
                                                <span class="text-sm text-gray-600 ml-2">({{ $class->enrollments_count }} students)</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Specific Emails -->
                            <div id="specific-emails" class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200" style="display: none;">
                                <h4 class="text-lg font-semibold text-green-900 mb-4">Email Addresses</h4>
                                <textarea class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none" 
                                          name="specific_emails" id="specific_emails" rows="4" 
                                          placeholder="parent1@example.com, parent2@example.com, parent3@example.com"></textarea>
                                <p class="text-sm text-green-700 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Enter email addresses separated by commas
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Content Section -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-edit mr-3"></i>
                                Email Content
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject Line</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('subject') border-red-500 @enderror" 
                                       name="subject" id="subject" value="{{ old('subject') }}" 
                                       placeholder="Enter email subject..." required>
                                @error('subject')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none @error('message') border-red-500 @enderror" 
                                          name="message" id="message" rows="12" 
                                          placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-magic mr-1"></i>
                                    The message will be personalized with parent and student names where available.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Preview & Actions -->
                <div class="space-y-6">
                    <!-- Preview Panel -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-eye mr-3"></i>
                                Preview & Send
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <button type="button" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center space-x-2" id="previewBtn">
                                <i class="fas fa-search"></i>
                                <span>Preview Recipients</span>
                            </button>

                            <div id="preview-results" class="space-y-4" style="display: none;">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-blue-900">Recipients Found:</span>
                                        <span id="recipient-count" class="text-lg font-bold text-blue-600">0</span>
                                    </div>
                                </div>
                                
                                <div id="recipient-list" class="max-h-64 overflow-y-auto space-y-2">
                                    <!-- Recipients will be listed here -->
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition-all duration-300 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed" id="sendBtn" disabled>
                                <i class="fas fa-paper-plane"></i>
                                <span>Send Email</span>
                            </button>

                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-amber-900">Important Notice</p>
                                        <p class="text-sm text-amber-700 mt-1">Please preview before sending. This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-lightbulb mr-3"></i>
                                Quick Tips
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-700">Use clear, concise subject lines</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-700">Personalize messages with student names</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-700">Always preview before sending</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-700">Keep messages professional and friendly</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>

<script>
// Branch selection function
function selectBranch(type) {
    const branchSelection = document.getElementById('branch-selection');
    const previewResults = document.getElementById('preview-results');
    const sendBtn = document.getElementById('sendBtn');
    
    // Hide branch selection first
    branchSelection.style.display = 'none';
    
    // Show relevant section
    if (type === 'specific') {
        branchSelection.style.display = 'block';
    }
    
    // Clear preview when branch selection changes
    previewResults.style.display = 'none';
    sendBtn.disabled = true;
}

// Target selection function
function selectTarget(type) {
    const classSelection = document.getElementById('class-selection');
    const specificEmails = document.getElementById('specific-emails');
    const previewResults = document.getElementById('preview-results');
    const sendBtn = document.getElementById('sendBtn');
    
    // Hide all sections first
    classSelection.style.display = 'none';
    specificEmails.style.display = 'none';
    
    // Show relevant section
    if (type === 'class') {
        classSelection.style.display = 'block';
    } else if (type === 'specific') {
        specificEmails.style.display = 'block';
    }
    
    // Clear preview when target type changes
    previewResults.style.display = 'none';
    sendBtn.disabled = true;
}

document.addEventListener('DOMContentLoaded', function() {
    const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
    const branchSelectionRadios = document.querySelectorAll('input[name="branch_selection"]');
    const previewBtn = document.getElementById('previewBtn');
    const sendBtn = document.getElementById('sendBtn');
    const previewResults = document.getElementById('preview-results');

    // Handle branch selection changes
    branchSelectionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectBranch(this.value);
        });
    });

    // Handle target type changes
    targetTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectTarget(this.value);
        });
    });

    // Preview functionality with enhanced UI
    previewBtn.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('bulkEmailForm'));
        
        // Debug: Log form data
        console.log('Form Data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Also log the form element to check if it exists
        const form = document.getElementById('bulkEmailForm');
        console.log('Form element:', form);
        console.log('Form fields:', form ? form.elements : 'Form not found');
        
        // Show loading state
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';
        previewBtn.disabled = true;
        
        fetch('{{ route("bulk-email.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Show detailed error information
                let errorMessage = 'Error: ' + data.error;
                if (data.details) {
                    errorMessage += '\n\nValidation Details:\n';
                    for (const [field, errors] of Object.entries(data.details)) {
                        errorMessage += `${field}: ${errors.join(', ')}\n`;
                    }
                }
                if (data.request_data) {
                    errorMessage += '\nRequest Data:\n' + JSON.stringify(data.request_data, null, 2);
                }
                console.error('Preview Error:', data);
                showNotification(errorMessage, 'error');
                return;
            }

            document.getElementById('recipient-count').textContent = data.recipient_count;
            
            const recipientList = document.getElementById('recipient-list');
            recipientList.innerHTML = '';
            
            if (data.recipients.length > 0) {
                data.recipients.forEach(recipient => {
                    const div = document.createElement('div');
                    div.className = 'bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow';
                    div.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">${recipient.parent_name || 'Unknown Parent'}</div>
                                <div class="text-sm text-gray-600">${recipient.email}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-user-graduate mr-1"></i>
                                    Student: ${recipient.student_name} - ${recipient.class_name}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-building mr-1"></i>
                                    Branch: ${recipient.branch_name || 'Unknown Branch'}
                                </div>
                            </div>
                            <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                Valid
                            </div>
                        </div>
                    `;
                    recipientList.appendChild(div);
                });
                
                if (data.recipient_count > 10) {
                    const moreDiv = document.createElement('div');
                    moreDiv.className = 'text-center py-2 text-sm text-gray-500 bg-gray-50 rounded-lg';
                    moreDiv.innerHTML = `<i class="fas fa-ellipsis-h mr-1"></i>... and ${data.recipient_count - 10} more recipients`;
                    recipientList.appendChild(moreDiv);
                }
            } else {
                recipientList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-search text-3xl mb-2"></i>
                        <p>No recipients found</p>
                        <p class="text-sm">Please check your selection criteria</p>
                    </div>
                `;
            }

            previewResults.style.display = 'block';
            sendBtn.disabled = data.recipient_count === 0;
            
            // Show success notification
            if (data.recipient_count > 0) {
                showNotification(`Found ${data.recipient_count} recipients ready for email`, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error previewing recipients. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            previewBtn.innerHTML = '<i class="fas fa-search"></i><span>Preview Recipients</span>';
            previewBtn.disabled = false;
        });
    });

    // Enhanced form submission
    document.getElementById('bulkEmailForm').addEventListener('submit', function(e) {
        const sendBtn = document.getElementById('sendBtn');
        if (!sendBtn.disabled) {
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';
            sendBtn.disabled = true;
        }
    });
});

// Notification function for better UX
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}
</script>
@endsection

