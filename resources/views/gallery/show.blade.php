@extends('layouts.app')

@section('title', $gallery->title . ' - School Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('gallery.index') }}" class="text-gray-400 hover:text-gray-500 transition-colors cursor-pointer">
                                <svg class="flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="sr-only">Gallery</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('gallery.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors cursor-pointer">Gallery</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-900">{{ $gallery->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Gallery Item Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Image Section -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ $gallery->image_url }}" 
                         alt="{{ $gallery->title }}" 
                         class="w-full h-auto object-cover">
                </div>
                
                <!-- Image Actions -->
                <div class="flex items-center justify-center space-x-4">
                    <button onclick="downloadImage('{{ $gallery->image_url }}', '{{ $gallery->title }}')" 
                            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </button>
                    
                    <button onclick="shareImage('{{ $gallery->image_url }}', '{{ $gallery->title }}')" 
                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Share
                    </button>
                </div>
            </div>

            <!-- Details Section -->
            <div class="space-y-6">
                <!-- Title and Category -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $gallery->title }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="inline-block px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ ucfirst($gallery->category) }}
                        </span>
                        @if($gallery->branch)
                        <span class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $gallery->branch->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($gallery->description)
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $gallery->description }}</p>
                </div>
                @endif

                <!-- Metadata -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Details</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Category:</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($gallery->category) }}</dd>
                        </div>
                        @if($gallery->branch)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Branch:</dt>
                            <dd class="text-sm text-gray-900">{{ $gallery->branch->name }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Added:</dt>
                            <dd class="text-sm text-gray-900">{{ $gallery->created_at->format('F j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Last Updated:</dt>
                            <dd class="text-sm text-gray-900">{{ $gallery->updated_at->format('F j, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Navigation -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Navigation</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('gallery.index') }}" 
                           class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center cursor-pointer">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Back to Gallery
                        </a>
                        
                        @if($gallery->branch)
                        <a href="{{ route('gallery.index') }}?branch={{ $gallery->branch->id }}" 
                           class="flex-1 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-center cursor-pointer">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            View Branch Gallery
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function downloadImage(imageUrl, title) {
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = title.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function shareImage(imageUrl, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Check out this image from our school gallery!',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}
</script>
@endpush

