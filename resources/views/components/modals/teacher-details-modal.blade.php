@props(['teacher' => null])

<div id="teacher-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Teacher Details</h3>
                <button onclick="closeTeacherDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="teacher-details-content" class="space-y-4">
                <!-- Teacher details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewTeacherDetails(teacherId) {
    // Fetch teacher details and populate modal
    fetch(`/admin/teachers/${teacherId}`)
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('teacher-details-content');
        content.innerHTML = `
            <!-- Header with Teacher Avatar and Basic Info -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4 shadow-lg">
                    <span class="text-3xl font-bold text-white">${data.teacher.name.charAt(0).toUpperCase()}</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">${data.teacher.name}</h2>
                <p class="text-gray-600">Teacher</p>
            </div>

            <!-- Personal Information Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 shadow-sm border border-blue-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="w-20 text-sm font-medium text-gray-600">Name:</span>
                            <span class="text-gray-800 font-medium">${data.teacher.name}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-sm font-medium text-gray-600">Email:</span>
                            <span class="text-gray-800 font-medium">${data.teacher.email}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-sm font-medium text-gray-600">Phone:</span>
                            <span class="text-gray-800 font-medium">${data.teacher.phone || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="w-20 text-sm font-medium text-gray-600">Address:</span>
                            <span class="text-gray-800 font-medium">${data.teacher.address || 'N/A'}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-sm font-medium text-gray-600">Joined:</span>
                            <span class="text-gray-800 font-medium">${new Date(data.teacher.created_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teaching Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 shadow-sm border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Total Classes</p>
                            <p class="text-3xl font-bold text-green-700">${data.teacher.teaching_classes?.length || 0}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 shadow-sm border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Total Subjects</p>
                            <p class="text-3xl font-bold text-purple-700">${data.teacher.subjects?.length || 0}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Subjects Section -->
            ${data.teacher.subjects && data.teacher.subjects.length > 0 ? `
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 mb-6 shadow-sm border border-orange-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Assigned Subjects</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.teacher.subjects.map(subject => `
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-orange-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-800">${subject.name}</div>
                                    <div class="text-sm text-gray-500">ID: ${subject.id}</div>
                                </div>
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}

            <!-- Current Classes Section -->
            ${data.teacher.teaching_classes && data.teacher.teaching_classes.length > 0 ? `
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-6 shadow-sm border border-teal-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Current Classes</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.teacher.teaching_classes.map(cls => `
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-teal-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-800">${cls.name}</div>
                                    <div class="text-sm text-gray-500">Grade: ${cls.grade_level || 'N/A'}</div>
                                </div>
                                <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}

            <!-- Empty State for No Classes/Subjects -->
            ${(!data.teacher.teaching_classes || data.teacher.teaching_classes.length === 0) && (!data.teacher.subjects || data.teacher.subjects.length === 0) ? `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Assignments Yet</h3>
                <p class="text-gray-500">This teacher hasn't been assigned to any classes or subjects yet.</p>
            </div>
            ` : ''}
        `;
        
        document.getElementById('teacher-details-modal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching teacher details');
    });
}

function closeTeacherDetailsModal() {
    document.getElementById('teacher-details-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const detailsModal = document.getElementById('teacher-details-modal');
    
    if (detailsModal && !detailsModal.querySelector('.relative').contains(event.target)) {
        if (!detailsModal.classList.contains('hidden')) {
            closeTeacherDetailsModal();
        }
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const detailsModal = document.getElementById('teacher-details-modal');
        
        if (detailsModal && !detailsModal.classList.contains('hidden')) {
            closeTeacherDetailsModal();
        }
    }
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced modal styling */
#teacher-details-modal .relative {
    max-height: 90vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

#teacher-details-modal .relative::-webkit-scrollbar {
    width: 6px;
}

#teacher-details-modal .relative::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 3px;
}

#teacher-details-modal .relative::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

#teacher-details-modal .relative::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Card hover effects */
.bg-white.rounded-lg.p-4.shadow-sm {
    transition: all 0.2s ease-in-out;
}

.bg-white.rounded-lg.p-4.shadow-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Gradient text effects */
.text-3xl.font-bold.text-white {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Icon animations */
.w-10.h-10.bg-blue-100.rounded-lg,
.w-10.h-10.bg-orange-100.rounded-lg,
.w-10.h-10.bg-teal-100.rounded-lg {
    transition: all 0.2s ease-in-out;
}

.w-10.h-10.bg-blue-100.rounded-lg:hover,
.w-10.h-10.bg-orange-100.rounded-lg:hover,
.w-10.h-10.bg-teal-100.rounded-lg:hover {
    transform: scale(1.05);
}

/* Modal animation */
#teacher-details-modal {
    transition: all 0.3s ease-in-out;
}

#teacher-details-modal:not(.hidden) {
    display: flex;
    align-items: center;
    justify-content: center;
}

#teacher-details-modal .relative {
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

/* Responsive adjustments */
@media (max-width: 768px) {
    #teacher-details-modal .relative {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
    
    .grid.grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

