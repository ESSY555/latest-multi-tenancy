{{-- Super Admin Dashboard Component --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Global Statistics --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Branches</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['branches'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ ($stats['teachers'] ?? 0) + ($stats['students'] ?? 0) + ($stats['parents'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-chalkboard text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Classes</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['classes'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Admissions</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['admissions'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- System Overview --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Management</h3>
        <div class="space-y-3">
            <a href="{{ route('branches.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-building text-blue-600 mr-3"></i>
                <span class="text-gray-700">Manage Branches</span>
            </a>
            <a href="{{ route('user-management') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-users text-green-600 mr-3"></i>
                <span class="text-gray-700">User Management</span>
            </a>
            <a href="{{ route('class-management') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-chalkboard-teacher text-purple-600 mr-3"></i>
                <span class="text-gray-700">Class Management</span>
            </a>
            <a href="{{ route('admin.form-teacher-assignments.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-user-tie text-indigo-600 mr-3"></i>
                <span class="text-gray-700">Form Teacher Management</span>
            </a>
            <a href="{{ route('gallery.admin') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-images text-orange-600 mr-3"></i>
                <span class="text-gray-700">Gallery Management</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Content Management</h3>
        <div class="space-y-3">
            <a href="{{ route('school-news.admin') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-newspaper text-blue-600 mr-3"></i>
                <span class="text-gray-700">School News</span>
            </a>
            <a href="{{ route('admin.syllabus.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-book text-green-600 mr-3"></i>
                <span class="text-gray-700">Syllabus Management</span>
            </a>
            <a href="{{ route('admin.elibrary.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-book-open text-purple-600 mr-3"></i>
                <span class="text-gray-700">E-Library Management</span>
            </a>
            <a href="{{ route('admin.materials.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-file-alt text-orange-600 mr-3"></i>
                <span class="text-gray-700">Study Materials</span>
            </a>
        </div>
    </div>
</div>

{{-- Global Monitoring --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Global System Overview</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['branches'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Active Branches</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600">{{ $stats['classes'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total Classes</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['admissions'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total Admissions</div>
        </div>
    </div>
</div>

