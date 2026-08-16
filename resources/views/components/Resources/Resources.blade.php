@props(['activeTab' => 'syllabus'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Resources Navigation Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex gap-2 overflow-x-auto px-3 py-2 sm:gap-4 sm:px-6 sm:py-0" aria-label="Resources Tabs">
            <button onclick="showResourceTab('syllabus')" class="resource-tab inline-flex shrink-0 py-3 px-2 sm:py-4 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="syllabus">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
                    </svg>
                    <span>Syllabus</span>
                </div>
            </button>
            
            <button onclick="showResourceTab('timetables')" class="resource-tab inline-flex shrink-0 py-3 px-2 sm:py-4 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="timetables">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Exam Timetables</span>
                </div>
            </button>
            
            <button onclick="showResourceTab('elibrary')" class="resource-tab inline-flex shrink-0 py-3 px-2 sm:py-4 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="elibrary">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
                    </svg>
                    <span>E-Library</span>
                </div>
            </button>
            
            <button onclick="showResourceTab('materials')" class="resource-tab inline-flex shrink-0 py-3 px-2 sm:py-4 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="materials">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                    </svg>
                    <span>Study Materials</span>
                </div>
            </button>
        </nav>
    </div>

    <!-- Resources Content -->
    <div class="p-4 sm:p-6">
        <!-- Syllabus Tab -->
        <div id="syllabus-tab" class="resource-content">
            <x-Resources.Syllabus />
        </div>

        <!-- Exam Timetables Tab -->
        <div id="timetables-tab" class="resource-content hidden">
            <x-Resources.ExamTimetables />
        </div>

        <!-- E-Library Tab -->
        <div id="elibrary-tab" class="resource-content hidden">
            <x-Resources.ELibrary />
        </div>

        <!-- Study Materials Tab -->
        <div id="materials-tab" class="resource-content hidden">
            <x-Resources.StudyMaterials />
        </div>
    </div>
</div>

<script>
function showResourceTab(tabName) {
    // Hide all content tabs
    const contentTabs = document.querySelectorAll('.resource-content');
    contentTabs.forEach(tab => {
        tab.classList.add('hidden');
    });

    // Remove active state from all tab buttons
    const tabButtons = document.querySelectorAll('.resource-tab');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });

    // Show selected content tab
    const selectedTab = document.getElementById(tabName + '-tab');
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
    }

    // Add active state to selected tab button
    const selectedButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (selectedButton) {
        selectedButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        selectedButton.classList.add('border-blue-500', 'text-blue-600');
    }
}

// Initialize with default tab
document.addEventListener('DOMContentLoaded', function() {
    showResourceTab('syllabus');
});
</script>

<style>
.resource-tab {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}

.resource-tab.active {
    @apply border-blue-500 text-blue-600;
}

.resource-content {
    transition: opacity 0.3s ease-in-out;
}
</style>

