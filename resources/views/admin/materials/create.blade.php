@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Upload New Material</h1>
            <a href="{{ route('admin.materials.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Materials
            </a>
        </div>

        <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Material Title</label>
                    <input type="text" id="title" name="title" placeholder="e.g., Mathematics Notes - Chapter 1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="e.g., Mathematics" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Class Level -->
                <div>
                    <label for="class_level" class="block text-sm font-medium text-gray-700 mb-2">Class Level (Optional)</label>
                    <input type="text" id="class_level" name="class_level" placeholder="e.g., JSS1, SS2, Grade 10" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Material Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Material Type</label>
                    <select id="type" name="type" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="PDF">PDF</option>
                        <option value="Video">Video</option>
                        <option value="Document">Document</option>
                        <option value="Presentation">Presentation</option>
                        <option value="Image">Image</option>
                        <option value="Audio">Audio</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div class="md:col-span-2">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                    <input type="file" id="file" name="file" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, PPT, PPTX, MP4, AVI, MP3, JPG, PNG - Max 100MB</p>
                </div>

                <!-- Branch -->
                <div class="md:col-span-2">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch (Optional)</label>
                    <select id="branch_id" name="branch_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Branch (Optional)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Brief description of the material content..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>



            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.materials.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Upload Material
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

