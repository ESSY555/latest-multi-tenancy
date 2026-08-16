@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $material->title }}</h1>
            <a href="{{ route('admin.materials.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Materials
            </a>
        </div>

        <!-- Material Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Material Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Title:</span>
                        <span class="ml-2 text-gray-900">{{ $material->title }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Subject:</span>
                        <span class="ml-2 text-gray-900">{{ $material->subject }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Type:</span>
                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $material->type }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Class Level:</span>
                        <span class="ml-2 text-gray-900">{{ $material->class_level ?: 'Not specified' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Branch:</span>
                        <span class="ml-2 text-gray-900">{{ $material->branch ? $material->branch->name : 'Not specified' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">File Details</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">File Size:</span>
                        <span class="ml-2 text-gray-900">{{ $material->formatted_file_size }}</span>
                    </div>
                    @if($material->duration)
                    <div>
                        <span class="font-medium text-gray-700">Duration:</span>
                        <span class="ml-2 text-gray-900">{{ $material->formatted_duration }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="font-medium text-gray-700">Uploaded:</span>
                        <span class="ml-2 text-gray-900">{{ $material->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="ml-2 px-2 py-1 {{ $material->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm rounded-full">
                            {{ $material->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($material->description)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
            <p class="text-gray-700">{{ $material->description }}</p>
        </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-2">Views</h4>
                <p class="text-2xl font-bold text-blue-600">{{ $material->views }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-2">Downloads</h4>
                <p class="text-2xl font-bold text-green-600">{{ $material->downloads }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <h4 class="font-semibold text-purple-900 mb-2">Uploaded By</h4>
                <p class="text-lg font-medium text-purple-600">{{ $material->uploader ? $material->uploader->name : 'Unknown' }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.materials.edit', $material) }}" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Material
            </a>
            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Material
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

