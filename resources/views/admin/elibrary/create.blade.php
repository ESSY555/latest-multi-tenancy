@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New Book</h1>
            <a href="{{ route('admin.elibrary.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Library
            </a>
        </div>

        <form action="{{ route('admin.elibrary.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Book Title</label>
                    <input type="text" id="title" name="title" placeholder="e.g., Advanced Mathematics" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                    <input type="text" id="author" name="author" placeholder="e.g., Dr. Smith" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- ISBN -->
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                    <input type="text" id="isbn" name="isbn" placeholder="e.g., 978-1234567890" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category" name="category" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                        <option value="Academic">Academic</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Reference">Reference</option>
                        <option value="Textbook">Textbook</option>
                    </select>
                </div>

                <!-- Publication Year -->
                <div>
                    <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-2">Publication Year</label>
                    <input type="number" id="publication_year" name="publication_year" min="1900" max="2030" placeholder="e.g., 2024" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Publisher</label>
                    <input type="text" id="publisher" name="publisher" placeholder="e.g., Oxford University Press" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                    <select id="language" name="language" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Language</option>
                        <option value="English">English</option>
                        <option value="French">French</option>
                        <option value="Spanish">Spanish</option>
                        <option value="Arabic">Arabic</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Book Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Brief description of the book content..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- File Uploads -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- PDF File Upload -->
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">
                        PDF File <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Upload the book's PDF file (max 50MB)</p>
                </div>

                <!-- Cover Image Upload -->
                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Image <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Upload a cover image (max 5MB)</p>
                </div>
            </div>

            <!-- Status and Branch -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="available">Available</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>

                <!-- Branch -->
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                    <select id="branch_id" name="branch_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Branch (Optional)</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.elibrary.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

