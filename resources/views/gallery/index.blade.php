@extends('layouts.app')

@section('title', 'School Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">School Gallery</h1>
            <p class="text-xl text-blue-100">Explore our school's vibrant moments, achievements, and memories</p>
        </div>
    </div>

    <!-- Gallery Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Category Filter -->
        @if($categories->count() > 0)
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <button onclick="filterByCategory('all')" 
                        class="category-filter px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 cursor-pointer active-category">
                    All
                </button>
                @foreach($categories as $category)
                <button onclick="filterByCategory('{{ $category }}')" 
                        class="category-filter px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    {{ ucfirst($category) }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Gallery Grid -->
        <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($galleries as $gallery)
            <div class="gallery-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer" 
                 data-category="{{ $gallery->category }}">
                <div class="relative group">
                    <img src="{{ $gallery->image_url }}" 
                         alt="{{ $gallery->title }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center text-white">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                            <span class="text-sm font-medium">View Details</span>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $gallery->title }}</h3>
                    @if($gallery->description)
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $gallery->description }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ ucfirst($gallery->category) }}
                        </span>
                        @if($gallery->branch)
                        <span class="text-xs text-gray-500">{{ $gallery->branch->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No gallery items yet</h3>
                <p class="text-gray-500">Check back soon for exciting school moments!</p>
            </div>
            @endforelse
        </div>

        <!-- Load More Button (if needed) -->
        @if($galleries->count() > 12)
        <div class="text-center mt-8">
            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                Load More
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="relative max-w-4xl w-full mx-2 sm:mx-4">
            <!-- Close Button -->
            <button onclick="closeLightbox()" class="absolute -top-2 -right-2 sm:top-4 sm:right-4 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 sm:p-3 transition-all duration-200 cursor-pointer z-10 shadow-lg">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Image Container -->
            <div class="bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-2xl">
                <img id="lightbox-image" src="" alt="" class="w-full h-auto max-h-[70vh] sm:max-h-[80vh] object-contain">
                <div class="p-4 sm:p-6">
                    <h3 id="lightbox-title" class="text-lg sm:text-2xl font-bold text-gray-900 mb-2"></h3>
                    <p id="lightbox-description" class="text-sm sm:text-base text-gray-600"></p>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-4">
                        <span id="lightbox-category" class="inline-block px-3 py-1 text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 rounded-full w-fit"></span>
                        <span id="lightbox-branch" class="text-xs sm:text-sm text-gray-500"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Category filtering
function filterByCategory(category) {
    // Update active category button
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.classList.remove('active-category', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('active-category', 'bg-blue-600', 'text-white');
    
    // Filter gallery items
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
            item.style.animation = 'fadeIn 0.5s ease-in-out';
        } else {
            item.style.display = 'none';
        }
    });
}

// Lightbox functionality
function openLightbox(imageSrc, title, description, category, branch) {
    document.getElementById('lightbox-image').src = imageSrc;
    document.getElementById('lightbox-title').textContent = title;
    document.getElementById('lightbox-description').textContent = description || '';
    document.getElementById('lightbox-category').textContent = category ? category.charAt(0).toUpperCase() + category.slice(1) : '';
    document.getElementById('lightbox-branch').textContent = branch || '';
    
    document.getElementById('lightbox-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Add click event to gallery items
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            const title = this.querySelector('h3').textContent;
            const description = this.querySelector('p')?.textContent || '';
            const category = this.dataset.category;
            const branch = this.querySelector('.text-xs.text-gray-500')?.textContent || '';
            
            openLightbox(img.src, title, description, category, branch);
        });
    });
    
    // Close lightbox on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
    
    // Close lightbox on outside click
    document.getElementById('lightbox-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
});

// Add fadeIn animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>
@endpush

