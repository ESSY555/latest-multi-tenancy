@extends('layouts.dashboard')

@section('title', 'Score Entry Sheet')

@section('dashboard')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 uppercase">Score Entry Sheet</h1>
            <p class="text-gray-600 mt-2">Recording marks for <span class="font-bold text-blue-600">{{ $subject->name ?? 'N/A' }}</span> in <span class="font-bold text-blue-600">{{ $class->name ?? 'N/A' }}</span></p>
            <div class="mt-4 flex flex-wrap gap-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-widest">Term: {{ $currentTerm->name ?? 'N/A' }}</span>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-widest">Year: {{ $currentTerm->academicYear->name ?? 'N/A' }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-md shadow-sm flex items-start">
                <svg class="h-5 w-5 mr-2 mt-0.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <form method="POST" action="{{ route('teacher.save-scores') }}">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subject->id ?? '' }}">
                <input type="hidden" name="class_id" value="{{ $class->id ?? '' }}">
                <input type="hidden" name="term_id" id="term_id_input" value="{{ $currentTerm->id ?? '' }}">

                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-end gap-4">
                    <div class="w-full md:w-60">
                        <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Result Type</label>
                        <select name="result_type" id="result_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded text-xs font-bold uppercase"
                            onchange="toggleResultType()">
                            <option value="termly" {{ ($resultType ?? 'termly') === 'termly' ? 'selected' : '' }}>Termly</option>
                            <option value="mock" {{ ($resultType ?? 'termly') === 'mock' ? 'selected' : '' }}>Mock</option>
                        </select>
                    </div>
                    <div class="w-full md:w-72 hidden" id="mock_exam_wrap">
                        <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Mock Exam</label>
                        <select name="mock_exam_id" id="mock_exam_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded text-xs font-bold uppercase">
                            <option value="">Select Mock Exam</option>
                            @foreach(($mockExams ?? collect()) as $mockExam)
                                <option value="{{ $mockExam->id }}">{{ $mockExam->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-[11px] text-gray-500 font-semibold" id="term_hint">
                        Current term: {{ $currentTerm->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900 text-white uppercase text-[10px] tracking-widest">
                                <th class="px-6 py-4 font-black">Student Name</th>
                                <th class="px-6 py-4 font-black text-center w-32">CA 1 (10)</th>
                                <th class="px-6 py-4 font-black text-center w-32">CA 2 (10)</th>
                                <th class="px-6 py-4 font-black text-center w-32">CA 3 (10)</th>
                                <th class="px-6 py-4 font-black text-center w-32">EXAM (70)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $profile)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 uppercase text-sm">{{ $profile->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-medium">ID: {{ $profile->admission_number ?? 'N/A' }}</div>
                                    <input type="hidden" name="student_id[]" value="{{ $profile->user->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="cat1[]" min="0" max="10" step="0.5" placeholder="0.0"
                                           class="w-full px-3 py-2 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="npw[]" min="0" max="10" step="0.5" placeholder="0.0"
                                           class="w-full px-3 py-2 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="cat2[]" min="0" max="10" step="0.5" placeholder="0.0"
                                           class="w-full px-3 py-2 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="exam[]" min="0" max="70" step="0.5" placeholder="0.0"
                                           class="w-full px-3 py-2 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-sm">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 font-bold uppercase tracking-widest italic">No students found in this class</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-blue-600 text-white font-black rounded-full hover:bg-blue-700 active:transform active:scale-95 transition-all shadow-xl uppercase tracking-widest text-xs">
                        Save Academic Scores
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleResultType() {
        const typeEl = document.getElementById('result_type');
        const mockWrap = document.getElementById('mock_exam_wrap');
        const termHint = document.getElementById('term_hint');
        const termInput = document.getElementById('term_id_input');
        if (!typeEl || !mockWrap || !termHint || !termInput) return;

        const isMock = typeEl.value === 'mock';
        mockWrap.classList.toggle('hidden', !isMock);
        termHint.classList.toggle('hidden', isMock);

        if (isMock) {
            termInput.value = '';
        } else {
            termInput.value = "{{ $currentTerm->id ?? '' }}";
        }
    }

    document.addEventListener('DOMContentLoaded', toggleResultType);
</script>
@endpush

