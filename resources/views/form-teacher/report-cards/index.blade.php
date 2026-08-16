@extends('layouts.dashboard')

@section('title', 'Student Report Cards')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">Termly Report Cards</h1>
                <p class="mt-2 text-gray-600">Review, comment, and sign student results for <span class="font-bold text-blue-600">{{ $class->name }}</span></p>
                
                <!-- Selection Controls -->
                <div class="mt-6">
                    <form action="{{ route('form-teacher.report-cards') }}" method="GET" class="flex flex-wrap items-center gap-3" id="selectionForm">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Academic Year</label>
                            <div class="relative min-w-[200px]">
                                <select name="year_id" onchange="this.form.submit()" class="appearance-none w-full bg-white border border-gray-200 text-gray-900 text-xs font-bold rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 pr-10 shadow-sm uppercase tracking-wider transition-all">
                                    @foreach($allYears as $year)
                                        <option value="{{ $year->id }}" {{ $selectedYear && $selectedYear->id == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }} {{ $year->is_active ? '● ACTIVE' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Current Term</label>
                            <div class="relative min-w-[160px]">
                                <select name="term_id" onchange="this.form.submit()" class="appearance-none w-full bg-white border border-gray-200 text-gray-900 text-xs font-bold rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 pr-10 shadow-sm uppercase tracking-wider transition-all">
                                    @forelse($allTerms as $term)
                                        <option value="{{ $term->id }}" {{ $currentTerm && $currentTerm->id == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }}
                                        </option>
                                    @empty
                                        <option value="">No terms defined</option>
                                    @endforelse
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex items-center space-x-6 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Class</p>
                    <p class="text-3xl font-black text-gray-900">{{ $enrollments->count() }}</p>
                </div>
                <div class="w-[1px] h-12 bg-gray-100"></div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Selected Year</p>
                    <p class="text-sm font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">{{ $selectedYear->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if(!$currentTerm)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-2xl shadow-sm mb-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-black text-red-800 uppercase tracking-wider">Setup Required</h3>
                        <p class="text-xs text-red-700 mt-1 font-medium">
                            No academic terms have been created for the selected session. Please contact the administrator.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Student Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($enrollments as $enrollment)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg group-hover:rotate-6 transition-transform">
                                <span class="text-xl font-black text-white">
                                    {{ substr($enrollment->student->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4 overflow-hidden">
                                <h3 class="text-lg font-bold text-gray-900 truncate leading-tight">{{ $enrollment->student->name }}</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">ID: {{ $enrollment->student->student_id ?? 'FT-' . $enrollment->student->id }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Academic Status</span>
                                @if($enrollment->student->status === 'active')
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-[10px] font-black rounded-md uppercase">Enrolled</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-50 text-red-700 text-[10px] font-black rounded-md uppercase">Suspended</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Result Status</span>
                                @if($currentTerm && $enrollment->student->results_count > 0)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-black rounded-md uppercase">{{ $enrollment->student->results_count }} Recorded</span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-black rounded-md uppercase italic">Missing Scores</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Gender</span>
                                <span class="text-gray-900 text-xs font-bold uppercase italic">{{ $enrollment->student->studentProfile->gender ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50">
                            @if($currentTerm)
                                <a href="{{ route('result.student-sheet', ['studentId' => $enrollment->student->id, 'termId' => $currentTerm->id]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3.5 bg-gray-900 text-white text-[11px] font-black rounded-xl hover:bg-blue-600 transition-all group-hover:shadow-lg group-hover:shadow-blue-200 uppercase tracking-widest">
                                    <i class="fas fa-file-signature mr-2 opacity-70"></i>Manage Report Card
                                </a>
                            @else
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-3.5 bg-gray-200 text-gray-400 text-[11px] font-black rounded-xl cursor-not-allowed uppercase tracking-widest">
                                    <i class="fas fa-lock mr-2 opacity-50"></i>Select Term First
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="bg-gray-50 h-24 w-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-graduate text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase">No Students Found</h3>
                    <p class="text-gray-500 max-w-xs mx-auto mt-3 font-medium">There are currently no students assigned to your class records.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.group:hover .shadow-blue-200 {
    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2), 0 4px 6px -2px rgba(37, 99, 235, 0.1);
}
</style>
@endsection

