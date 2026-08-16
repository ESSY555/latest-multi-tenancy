@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">E-Library Management</h1>
            <a href="{{ route('admin.elibrary.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Book
            </a>
        </div>

        <!-- Library Stats -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Library Overview</h3>
                    <p class="text-blue-700">Total Books: 150 | Available: 120 | Borrowed: 30</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Active</span>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search books by title, author, or ISBN..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    <option value="fiction">Fiction</option>
                    <option value="non-fiction">Non-Fiction</option>
                    <option value="academic">Academic</option>
                    <option value="reference">Reference</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="reserved">Reserved</option>
                </select>
            </div>
        </div>

        <!-- Books Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">ISBN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Sample Books -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Advanced Mathematics</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Dr. Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Academic</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">978-1234567890</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Available</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.elibrary.edit', 1) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.elibrary.destroy', 1) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">English Literature</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Prof. Johnson</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Academic</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">978-0987654321</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Borrowed</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.elibrary.edit', 2) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.elibrary.destroy', 2) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Physics Fundamentals</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Dr. Williams</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Academic</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">978-1122334455</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Available</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.elibrary.edit', 3) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.elibrary.destroy', 3) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
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
                <span class="font-medium">Total Books:</span> 150
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
                <h4 class="font-semibold text-blue-900 mb-2">Total Books</h4>
                <p class="text-2xl font-bold text-blue-600">150</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-2">Available</h4>
                <p class="text-2xl font-bold text-green-600">120</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <h4 class="font-semibold text-yellow-900 mb-2">Borrowed</h4>
                <p class="text-2xl font-bold text-yellow-600">30</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <h4 class="font-semibold text-purple-900 mb-2">Categories</h4>
                <p class="text-2xl font-bold text-purple-600">8</p>
            </div>
        </div>
    </div>
</div>
@endsection

