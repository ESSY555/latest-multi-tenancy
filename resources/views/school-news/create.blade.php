@extends('layouts.dashboard')

@section('title', 'Create News Article')

@section('dashboard')
<!-- Header -->
<section class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 py-16">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Create News Article</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Share important updates, achievements, and announcements with the school community
        </p>
    </div>
</section>

<!-- Create Form -->
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('school-news.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Article Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Enter the article title"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Selection -->
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" 
                            name="branch_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('branch_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Excerpt (Optional)
                    </label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('excerpt') border-red-500 @enderror"
                              placeholder="A brief summary of the article (max 500 characters)">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">A short summary that will appear in news listings. Leave empty to auto-generate from content.</p>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Article Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="12"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                              placeholder="Write your article content here..."
                              required>{{ old('content') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">You can use basic HTML tags for formatting (e.g., &lt;strong&gt;, &lt;em&gt;, &lt;br&gt;).</p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Image (Optional)
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Upload an image (JPEG, PNG, JPG, GIF) up to 2MB. Recommended size: 1200x630 pixels.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Publishing Options -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                    
                    <div class="space-y-4">
                        <!-- Publish Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_published" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                Publish immediately
                            </label>
                        </div>

                        <!-- Scheduled Publishing -->
                        <div id="schedule-section" class="{{ old('is_published') ? 'hidden' : '' }}">
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Schedule for later (Optional)
                            </label>
                            <input type="datetime-local" 
                                   id="published_at" 
                                   name="published_at" 
                                   value="{{ old('published_at') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep as draft until manually published.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Article
                    </button>
                    <a href="{{ route('school-news.index') }}" 
                       class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const publishCheckbox = document.getElementById('is_published');
    const scheduleSection = document.getElementById('schedule-section');
    
    publishCheckbox.addEventListener('change', function() {
        if (this.checked) {
            scheduleSection.classList.add('hidden');
        } else {
            scheduleSection.classList.remove('hidden');
        }
    });
});
</script>
@endpush
@endsection

