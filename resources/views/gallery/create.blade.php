@extends('layouts.dashboard')

@section('title', 'Upload Gallery Item')

@section('dashboard')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Upload Gallery Item</h1>
                    <p class="mt-2 text-gray-600">Add a new image to the school gallery</p>
                </div>
                <a href="{{ route('gallery.admin') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Gallery
                </a>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" id="gallery-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Image *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden mb-4">
                                    <img id="preview-img" src="" alt="Preview" class="mx-auto h-32 w-auto rounded-lg">
                                </div>
                                <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter image title"
                               required>
                        @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter image description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category and Branch -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select id="category" 
                                    name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" @if(old('category') == $category) selected @endif>
                                    {{ ucfirst($category) }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                            <select id="branch_id" 
                                    name="branch_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Global (All Branches)</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" @if(old('branch_id') == $branch->id) selected @endif>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Display Order and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', 0) }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                            @error('display_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       @if(old('is_active', true)) checked @endif
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Active</label>
                                <p class="text-gray-500">Make this item visible in the public gallery</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('gallery.admin') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                            Upload Image
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-icon').classList.add('hidden');
        };
        reader.readAsDataURL(file);
        
        // Validate file size
        if (file.size > 2 * 1024 * 1024) { // 2MB
            alert('File size must be less than 2MB');
            this.value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-icon').classList.remove('hidden');
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPG, GIF)');
            this.value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-icon').classList.remove('hidden');
        }
    }
});

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('image');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
    }
});

// Form validation
document.getElementById('gallery-form').addEventListener('submit', function(e) {
    const image = document.getElementById('image').files[0];
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;
    
    if (!image) {
        e.preventDefault();
        alert('Please select an image to upload');
        return;
    }
    
    if (!title) {
        e.preventDefault();
        alert('Please enter a title for the image');
        document.getElementById('title').focus();
        return;
    }
    
    if (!category) {
        e.preventDefault();
        alert('Please select a category');
        document.getElementById('category').focus();
        return;
    }
});
</script>
@endpush
@endsection

