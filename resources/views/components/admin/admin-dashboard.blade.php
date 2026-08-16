{{-- Admin Dashboard Component --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Branch Statistics --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Teachers</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['teachers'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Students</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['students'] ?? 0 }}</p>
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
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending Lessons</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_lesson_plans'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('teachers.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                <span class="text-gray-700">Add New Teacher</span>
            </a>
            <a href="{{ route('students.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                <span class="text-gray-700">Add New Student</span>
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-chalkboard-teacher text-blue-600 mr-3"></i>
                <span class="text-gray-700">Manage Teachers</span>
            </a>
            <a href="{{ route('admin.students.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-user-graduate text-green-600 mr-3"></i>
                <span class="text-gray-700">Manage Students</span>
            </a>
            <a href="{{ route('admin.admissions.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-user-plus text-purple-600 mr-3"></i>
                <span class="text-gray-700">Review Admissions</span>
            </a>
            <a href="{{ route('lesson-plans.index') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-book-open text-indigo-600 mr-3"></i>
                <span class="text-gray-700">View Lesson Plans</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Admissions</h3>
        @if(isset($recentAdmissions) && $recentAdmissions->count() > 0)
        <div class="space-y-3">
            @foreach($recentAdmissions->take(5) as $admission)
            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $admission->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $admission->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $admission->status_color }}">
                    {{ ucfirst($admission->status) }}
                </span>
            </div>
            @endforeach
            <div class="pt-2">
                <a href="{{ route('admin.admissions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All Admissions →
                </a>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-user-plus text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-500 text-sm">No recent admissions</p>
            <a href="{{ route('admin.admissions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                View All Admissions
            </a>
        </div>
        @endif
    </div>
</div>

{{-- System Status --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-900">System Online</p>
            <p class="text-xs text-gray-500">All services running</p>
        </div>
        <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-database text-blue-600 text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-900">Database</p>
            <p class="text-xs text-gray-500">Connected & healthy</p>
        </div>
        <div class="text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-900">Security</p>
            <p class="text-xs text-gray-500">Protected & monitored</p>
        </div>
    </div>
</div>

