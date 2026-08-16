@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New Syllabus</h1>
            <a href="{{ route('admin.syllabus.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Syllabus
            </a>
        </div>

        <form action="{{ route('admin.syllabus.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class -->
                <div>
                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select id="class" name="class" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Class</option>
                        <option value="JSS1">JSS1</option>
                        <option value="JSS2">JSS2</option>
                        <option value="JSS3">JSS3</option>
                        <option value="SS1">SS1</option>
                        <option value="SS2">SS2</option>
                        <option value="SS3">SS3</option>
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="e.g., Mathematics" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Term -->
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                    <select id="term" name="term" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Term</option>
                        <option value="First Term">First Term</option>
                        <option value="Second Term">Second Term</option>
                        <option value="Third Term">Third Term</option>
                    </select>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <input type="text" id="duration" name="duration" placeholder="e.g., 12 weeks" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Topics -->
            <div>
                <label for="topics" class="block text-sm font-medium text-gray-700 mb-2">Topics</label>
                <textarea id="topics" name="topics" rows="4" placeholder="Enter the main topics covered in this syllabus..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>

            <!-- Learning Objectives -->
            <div>
                <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">Learning Objectives (Optional)</label>
                <textarea id="objectives" name="objectives" rows="3" placeholder="What students should be able to achieve by the end of this term..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.syllabus.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Syllabus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

