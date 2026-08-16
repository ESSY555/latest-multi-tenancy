@extends('layouts.dashboard')

@section('title', 'Teacher Details')

@section('dashboard')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Teacher Details</h1>
            <p class="text-gray-600">View and manage teacher information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('teacher-management.index') }}" 
               class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            
            @if($teacher->status === 'active')
                <form action="{{ route('teacher-management.deactivate', $teacher) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors"
                            onclick="return confirm('Are you sure you want to deactivate this teacher? They will not be able to login.')">
                        <i class="fas fa-pause mr-2"></i>Deactivate Teacher
                    </button>
                </form>
            @else
                <form action="{{ route('teacher-management.activate', $teacher) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                            onclick="return confirm('Are you sure you want to activate this teacher?')">
                        <i class="fas fa-play mr-2"></i>Activate Teacher
                    </button>
                </form>
            @endif
            
            <form action="{{ route('teacher-management.destroy', $teacher) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        onclick="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.')">
                    <i class="fas fa-trash mr-2"></i>Delete Teacher
                </button>
            </form>
        </div>
    </div>

    <!-- Teacher Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Teacher Information</h3>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-20 w-20">
                            <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-2xl font-medium text-gray-700">
                                    {{ substr($teacher->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900">{{ $teacher->name }}</h4>
                            <p class="text-gray-600">{{ $teacher->email }}</p>
                            <div class="mt-2">
                                @if($teacher->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $teacher->phone ?? 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $teacher->address ?? 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Login</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $teacher->last_login_at ? $teacher->last_login_at->format('M d, Y g:i A') : 'Never' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Branch Assignment</h3>
        </div>
        
        <div class="px-6 py-4">
            @if($teacher->branches->count() > 0)
                <div class="space-y-3">
                    @foreach($teacher->branches as $branch)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $branch->name }}</h4>
                                <p class="text-sm text-gray-600">Role: {{ ucfirst($branch->pivot->role) }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($branch->pivot->role) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No branch assignments found.</p>
            @endif
        </div>
    </div>

    <!-- Teaching Classes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Teaching Classes</h3>
        </div>
        
        <div class="px-6 py-4">
            @if($teacher->teachingClasses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($teacher->teachingClasses as $class)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-900">{{ $class->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $class->branch->name }}</p>
                            <p class="text-sm text-gray-500">Grade: {{ $class->grade_level ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No teaching classes assigned.</p>
            @endif
        </div>
    </div>

    <!-- Subjects -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Subjects</h3>
        </div>
        
        <div class="px-6 py-4">
            @if($teacher->subjects->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($teacher->subjects as $subject)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ $subject->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No subjects assigned.</p>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success message
        const successMessage = document.createElement('div');
        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successMessage.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        `;
        document.body.appendChild(successMessage);
        
        // Remove after 5 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    });
</script>
@endif
@endsection

