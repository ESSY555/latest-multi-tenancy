@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Study Materials Management</h1>
            <a href="{{ route('admin.materials.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Upload New Material
            </a>
        </div>

        <!-- Materials Overview -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Materials Overview</h3>
                    <p class="text-blue-700">Total Materials: {{ $materials->total() }} | PDFs: {{ $materials->where('type', 'PDF')->count() }} | Videos: {{ $materials->where('type', 'Video')->count() }} | Documents: {{ $materials->where('type', 'Document')->count() }}</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Active</span>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search materials by title, subject, or class..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Classes</option>
                    <option value="jss1">JSS1</option>
                    <option value="jss2">JSS2</option>
                    <option value="jss3">JSS3</option>
                    <option value="ss1">SS1</option>
                    <option value="ss2">SS2</option>
                    <option value="ss3">SS3</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    <option value="pdf">PDF</option>
                    <option value="video">Video</option>
                    <option value="document">Document</option>
                    <option value="presentation">Presentation</option>
                </select>
            </div>
        </div>

        <!-- Materials Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Sample Materials -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Mathematics Notes - Chapter 1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mathematics</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">JSS1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">PDF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2.5 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="#" class="text-green-600 hover:text-green-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.materials.edit', 1) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.materials.destroy', 1) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">English Grammar Video</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">English Language</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">JSS2</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Video</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">15.2 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="#" class="text-green-600 hover:text-green-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.materials.edit', 2) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.materials.destroy', 2) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Physics Lab Report Template</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Physics</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">SS1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Document</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">1.8 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="#" class="text-green-600 hover:text-green-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.materials.edit', 3) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.materials.destroy', 3) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Export Options -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                <span class="font-medium">Total Materials:</span> 85
            </div>
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </button>
                <button class="px-4 py-2 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-2">Total Materials</h4>
                <p class="text-2xl font-bold text-blue-600">85</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <h4 class="font-semibold text-red-900 mb-2">PDFs</h4>
                <p class="text-2xl font-bold text-red-600">45</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-2">Videos</h4>
                <p class="text-2xl font-bold text-blue-600">25</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-2">Documents</h4>
                <p class="text-2xl font-bold text-green-600">15</p>
            </div>
        </div>
    </div>
</div>
@endsection

