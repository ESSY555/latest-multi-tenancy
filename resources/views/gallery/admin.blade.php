@extends('layouts.dashboard')

@section('title', 'Gallery Management')

@section('dashboard')
    <!-- Note: This page includes a sidebar navigation on the left side -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Gallery Management</h1>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('gallery.create') }}"
                    class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors w-full sm:w-auto text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Upload New Image
                </a>
                <a href="{{ route('gallery.index') }}" target="_blank"
                    class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors w-full sm:w-auto text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M6 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Public Gallery
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Category Filter -->
        <div class="mb-6">
            <select id="category-filter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Branch Overview Section -->
        <div class="mb-6 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Branch Overview: {{ $branch ? $branch->name : 'HQ' }}
                    </h2>
                    <p class="text-gray-600 mt-1">Activities and metrics for {{ $branch ? $branch->name : 'HQ' }} branch</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">{{ $galleries->count() }}</div>
                    <div class="text-sm text-gray-500">Total Images</div>
                </div>
            </div>

            <!-- Branch Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-blue-600">Active Images</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $galleries->where('is_active', true)->count() }}</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-green-600">Categories</div>
                    <div class="text-2xl font-bold text-green-900">{{ count($categories) }}</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-purple-600">Recent Uploads</div>
                    <div class="text-2xl font-bold text-purple-900">
                        {{ $galleries->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4">
                    <div class="text-sm font-medium text-orange-600">Total Images</div>
                    <div class="text-2xl font-bold text-orange-900">{{ $galleries->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Gallery Items Table -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gallery Items ({{ $galleries->count() }})</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($galleries as $gallery)
                            <tr class="gallery-row" data-category="{{ $gallery->category }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ $gallery->image_url }}"
                                            alt="{{ $gallery->title }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $gallery->title }}</div>
                                    @if($gallery->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $gallery->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($gallery->category === 'events') bg-purple-100 text-purple-800
                                            @elseif($gallery->category === 'activities') bg-green-100 text-green-800
                                            @elseif($gallery->category === 'facilities') bg-blue-100 text-blue-800
                                            @elseif($gallery->category === 'students') bg-yellow-100 text-yellow-800
                                            @elseif($gallery->category === 'teachers') bg-indigo-100 text-indigo-800
                                            @elseif($gallery->category === 'achievements') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                        {{ ucfirst($gallery->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $gallery->branch ? $gallery->branch->name : 'Global' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $gallery->id }})"
                                        class="status-toggle inline-flex px-2 py-1 text-xs font-semibold rounded-full cursor-pointer transition-colors
                                                @if($gallery->is_active) bg-green-100 text-green-800 hover:bg-green-200 @else bg-red-100 text-red-800 hover:bg-red-200 @endif"
                                        data-gallery-id="{{ $gallery->id }}"
                                        data-status="{{ $gallery->is_active ? '1' : '0' }}">
                                        {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $gallery->display_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('gallery.show', $gallery) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <a href="{{ route('gallery.edit', $gallery) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <button onclick="deleteGallery({{ $gallery->id }}, '{{ $gallery->title }}')"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No gallery items yet</h3>
                                        <p class="mb-4">Get started by uploading your first image to the gallery.</p>
                                        <a href="{{ route('gallery.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Upload First Image
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Gallery Item</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to delete "<span id="delete-title"></span>"? This
                        action cannot be undone.</p>
                </div>
                <div class="flex justify-center space-x-4 mt-4">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                        Cancel
                    </button>
                    <button onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let deleteGalleryId = null;

            // Category filtering
            document.getElementById('category-filter').addEventListener('change', function () {
                const category = this.value;
                const rows = document.querySelectorAll('.gallery-row');

                rows.forEach(row => {
                    if (!category || row.dataset.category === category) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Toggle gallery status
            function toggleStatus(galleryId) {
                const button = document.querySelector(`[data-gallery-id="${galleryId}"]`);
                const currentStatus = button.dataset.status === '1';

                fetch(`/gallery/${galleryId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button appearance
                            if (data.is_active) {
                                button.classList.remove('bg-red-100', 'text-red-800', 'hover:bg-red-200');
                                button.classList.add('bg-green-100', 'text-green-800', 'hover:bg-green-200');
                                button.textContent = 'Active';
                                button.dataset.status = '1';
                            } else {
                                button.classList.remove('bg-green-100', 'text-green-800', 'hover:bg-green-200');
                                button.classList.add('bg-red-100', 'text-red-800', 'hover:bg-red-200');
                                button.textContent = 'Inactive';
                                button.dataset.status = '0';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update status. Please try again.');
                    });
            }

            // Delete gallery item
            function deleteGallery(galleryId, title) {
                deleteGalleryId = galleryId;
                document.getElementById('delete-title').textContent = title;
                document.getElementById('delete-modal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('delete-modal').classList.add('hidden');
                deleteGalleryId = null;
            }

            function confirmDelete() {
                if (deleteGalleryId) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/gallery/${deleteGalleryId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Close modal on outside click
            document.getElementById('delete-modal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });
        </script>
    @endpush
@endsection
