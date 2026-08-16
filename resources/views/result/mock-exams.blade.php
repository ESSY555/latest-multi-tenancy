@extends('layouts.dashboard')

@section('title', 'Mock Exams')

@section('dashboard')
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-black text-gray-900 uppercase">Mock Exams</h1>
            <p class="text-sm text-gray-500">Create and activate mock exams for this branch.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Create Mock Exam</h2>
                <form method="POST" action="{{ route('result.mock-exams.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-600 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g. MOCK 2026" required>
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-600 mb-1">Academic Year</label>
                        <select name="academic_year_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">Select academic year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase font-bold text-gray-600 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs uppercase font-bold text-gray-600 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        Set as active
                    </label>

                    <div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">
                            Create Mock Exam
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Existing Mock Exams</h2>
                <div class="space-y-3">
                    @forelse($mockExams as $exam)
                        <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-gray-900">{{ $exam->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $exam->academicYear->name ?? 'N/A' }}
                                    @if($exam->start_date || $exam->end_date)
                                        | {{ optional($exam->start_date)->format('d M Y') ?? '-' }} - {{ optional($exam->end_date)->format('d M Y') ?? '-' }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($exam->is_active)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 font-bold uppercase">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600 font-bold uppercase">Inactive</span>
                                @endif
                                <form method="POST" action="{{ route('result.mock-exams.toggle', $exam) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-bold rounded {{ $exam->is_active ? 'bg-amber-500 text-white' : 'bg-blue-600 text-white' }}">
                                        {{ $exam->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No mock exams created yet.</p>
                    @endforelse
                </div>

                @if($mockExams instanceof \Illuminate\Contracts\Pagination\Paginator && method_exists($mockExams, 'links') && $mockExams->hasPages())
                    <div class="mt-6">
                        {{ $mockExams->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

