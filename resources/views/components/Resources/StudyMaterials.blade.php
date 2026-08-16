@props(['materials' => null, 'stats' => null])

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Study Materials</h2>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <input type="text" id="materials-search" placeholder="Search study materials..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <select id="subject-filter" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All Subjects</option>
            <option value="Mathematics">Mathematics</option>
            <option value="Science">Science</option>
            <option value="English">English</option>
            <option value="History">History</option>
            <option value="Geography">Geography</option>
            <option value="Literature">Literature</option>
            <option value="Computer Science">Computer Science</option>
        </select>
        <select id="type-filter" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All Types</option>
            <option value="PDF">PDF</option>
            <option value="Video">Video</option>
            <option value="Presentation">Presentation</option>
            <option value="Worksheet">Worksheet</option>
            <option value="Document">Document</option>
            <option value="Image">Image</option>
            <option value="Audio">Audio</option>
        </select>
    </div>

    <!-- Materials Grid -->
    <div id="materials-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Material Item 1 -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 bg-gradient-to-r from-red-50 to-pink-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-900">Algebra Practice Problems</h3>
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">PDF</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">Comprehensive practice problems for Grade 10 algebra with solutions.</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Subject: Mathematics</span>
                    <span class="text-xs text-gray-500">2.5 MB</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Uploaded: 3 days ago</span>
                    <span class="text-xs text-gray-500">Downloads: 45</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Download</button>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Material Item 2 -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 bg-gradient-to-r from-blue-50 to-indigo-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-900">Physics Lab Tutorial</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Video</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">Step-by-step guide for conducting physics experiments safely.</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Subject: Science</span>
                    <span class="text-xs text-gray-500">15:32 min</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Uploaded: 1 week ago</span>
                    <span class="text-xs text-gray-500">Views: 128</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Watch</button>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Material Item 3 -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 bg-gradient-to-r from-green-50 to-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                </svg>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-900">English Grammar Notes</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Presentation</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">Comprehensive grammar rules and examples for advanced English.</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Subject: English</span>
                    <span class="text-xs text-gray-500">8.2 MB</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Uploaded: 2 days ago</span>
                    <span class="text-xs text-gray-500">Downloads: 67</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Download</button>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Material Item 4 -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 bg-gradient-to-r from-purple-50 to-violet-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-900">History Timeline</h3>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Worksheet</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">Interactive timeline worksheet for World War II events.</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Subject: History</span>
                    <span class="text-xs text-gray-500">1.8 MB</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Uploaded: 5 days ago</span>
                    <span class="text-xs text-gray-500">Downloads: 34</span>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Download</button>
                    <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2">Total Materials</h4>
            <p class="text-2xl font-bold text-blue-600" data-stat="total">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h4 class="font-semibold text-green-900 mb-2">PDF Files</h4>
            <p class="text-2xl font-bold text-green-600" data-stat="pdfs">{{ $stats['pdfs'] ?? 0 }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h4 class="font-semibold text-yellow-900 mb-2">Videos</h4>
            <p class="text-2xl font-bold text-yellow-600" data-stat="videos">{{ $stats['videos'] ?? 0 }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h4 class="font-semibold text-purple-900 mb-2">Worksheets</h4>
            <p class="text-2xl font-bold text-purple-600" data-stat="worksheets">{{ $stats['worksheets'] ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Material Details Modal -->
<div id="material-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Material Details</h3>
                <button onclick="closeMaterialModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="material-modal-content" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>



<script>
// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('materials-search');
    const subjectFilter = document.getElementById('subject-filter');
    const typeFilter = document.getElementById('type-filter');

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Search function
    const performSearch = debounce(function() {
        const searchTerm = searchInput.value;
        const subject = subjectFilter.value;
        const type = typeFilter.value;

        // Make AJAX request to get filtered materials
        fetch(`{{ route('resources.materials.api.get') }}?search=${searchTerm}&subject=${subject}&type=${type}`)
            .then(response => response.json())
            .then(data => {
                updateMaterialsGrid(data.materials);
                updateStats(data.stats);
            })
            .catch(error => console.error('Error:', error));
    }, 300);

    // Add event listeners
    searchInput.addEventListener('input', performSearch);
    subjectFilter.addEventListener('change', performSearch);
    typeFilter.addEventListener('change', performSearch);
});

// Update materials grid
function updateMaterialsGrid(materials) {
    const grid = document.getElementById('materials-grid');
    if (materials.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No materials found</h3>
                <p class="text-gray-500">Try adjusting your search or filters to find what you're looking for.</p>
            </div>
        `;
        return;
    }

    grid.innerHTML = materials.map(material => `
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 ${getMaterialBgClass(material.type)} flex items-center justify-center">
                ${getMaterialIcon(material.type)}
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-900">${material.title}</h3>
                    <span class="px-2 py-1 ${getMaterialBadgeClass(material.type)} text-xs rounded-full">${material.type}</span>
                </div>
                <p class="text-sm text-gray-600 mb-3">${material.description || 'No description available.'}</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Subject: ${material.subject}</span>
                    <span class="text-xs text-gray-500">${material.type === 'Video' ? material.formatted_duration : material.formatted_file_size}</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500">Uploaded: ${material.time_ago}</span>
                    <span class="text-xs text-gray-500">${material.type === 'Video' ? 'Views: ' + material.views : 'Downloads: ' + material.downloads}</span>
                </div>
                <div class="flex gap-2">
                    ${material.type === 'Video' ? 
                        `<a href="/materials/view/${material.id}" target="_blank" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm text-center">Watch</a>` :
                        `<a href="/materials/download/${material.id}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm text-center">Download</a>`
                    }
                    <button onclick="showMaterialDetails(${material.id})" class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Update stats
function updateStats(stats) {
    const statElements = document.querySelectorAll('[data-stat]');
    statElements.forEach(element => {
        const statType = element.dataset.stat;
        if (stats[statType] !== undefined) {
            element.textContent = stats[statType];
        }
    });
}

// Helper functions for material styling
function getMaterialBgClass(type) {
    const classes = {
        'Video': 'bg-gradient-to-r from-blue-50 to-indigo-100',
        'PDF': 'bg-gradient-to-r from-red-50 to-pink-100',
        'Presentation': 'bg-gradient-to-r from-green-50 to-emerald-100',
        'Worksheet': 'bg-gradient-to-r from-purple-50 to-violet-100'
    };
    return classes[type] || 'bg-gradient-to-r from-gray-50 to-gray-100';
}

function getMaterialBadgeClass(type) {
    const classes = {
        'Video': 'bg-blue-100 text-blue-800',
        'PDF': 'bg-red-100 text-red-800',
        'Presentation': 'bg-green-100 text-green-800',
        'Worksheet': 'bg-purple-100 text-purple-800'
    };
    return classes[type] || 'bg-gray-100 text-gray-800';
}

function getMaterialIcon(type) {
    const icons = {
        'Video': `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>`,
        'PDF': `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>`,
        'Presentation': `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
        </svg>`,
        'Worksheet': `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>`
    };
    return icons[type] || `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
    </svg>`;
}

// Modal functions
function showMaterialDetails(materialId) {
    // Load material details via AJAX and show modal
    fetch(`/admin/materials/${materialId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('material-modal-content').innerHTML = html;
            document.getElementById('material-modal').classList.remove('hidden');
        });
}

function closeMaterialModal() {
    document.getElementById('material-modal').classList.add('hidden');
}


</script>

