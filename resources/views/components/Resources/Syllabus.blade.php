@props(['syllabus' => null])

@php
    $branchId = session('selected_branch_id');
    if (!$branchId) {
        $firstBranch = \App\Models\Branch::first();
        $branchId = $firstBranch ? $firstBranch->id : null;
    }
    $syllabi = $branchId ? \App\Models\Syllabus::where('branch_id', $branchId)->get() : collect();
@endphp

<div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Syllabus</h2>
        <div class="grid w-full grid-cols-1 gap-2 sm:w-auto sm:grid-cols-2 sm:gap-3">
            <a href="{{ route('syllabus.export.pdf') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('syllabus.export.excel') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a2 2 0 012-2z"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Syllabus List -->
    @if($syllabi->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($syllabi as $syllabus)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">{{ $syllabus->class }} - {{ $syllabus->subject }}</h3>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $syllabus->term }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($syllabus->topics, 100) }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Duration: {{ $syllabus->duration }}</span>
                        <span>Updated: {{ $syllabus->updated_at->diffForHumans() }}</span>
                    </div>
                    @if($syllabus->objectives)
                        <div class="mt-2 text-xs text-gray-600">
                            <strong>Objectives:</strong> {{ Str::limit($syllabus->objectives, 80) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Syllabus Available</h3>
            <p class="text-gray-500">No syllabus entries have been created yet.</p>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2">Total Syllabi</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $syllabi->count() }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h4 class="font-semibold text-green-900 mb-2">Classes</h4>
            <p class="text-2xl font-bold text-green-600">{{ $syllabi->unique('class')->count() }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h4 class="font-semibold text-yellow-900 mb-2">Terms</h4>
            <p class="text-2xl font-bold text-yellow-600">{{ $syllabi->unique('term')->count() }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h4 class="font-semibold text-purple-900 mb-2">Last Updated</h4>
            <p class="text-2xl font-bold text-purple-600">{{ $syllabi->count() > 0 ? $syllabi->sortByDesc('updated_at')->first()->updated_at->diffForHumans() : 'Never' }}</p>
        </div>
    </div>
</div>

