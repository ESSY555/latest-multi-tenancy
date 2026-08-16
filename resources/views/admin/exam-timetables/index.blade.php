@extends('layouts.dashboard')

@section('dashboard')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Exam Timetables Management</h1>
            @if($canManage ?? false)
                <a href="{{ route('exam-timetables.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create New Timetable
                </a>
            @endif
        </div>

        <form method="GET" class="mb-6">
            <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">Academic Section</label>
            <div class="flex items-center gap-2">
                <select name="academic_year_id" id="academic_year_id"
                    class="w-full md:w-96 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @forelse($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYear?->id === $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                        </option>
                    @empty
                        <option value="">No sections available</option>
                    @endforelse
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    View
                </button>
            </div>
        </form>

        <!-- Exam Timetable Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Scope</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Venue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($timetables as $timetable)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $timetable->exam_date?->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $timetable->exam_date?->format('l') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($timetable->start_time && $timetable->end_time)
                                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($timetable->end_time)->format('g:i A') }}
                                @elseif($timetable->start_time)
                                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('g:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $timetable->exam_scope === 'mock' ? 'Mock' : ($timetable->academicTerm?->name ?? 'Term') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $timetable->schoolClass?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $timetable->subject?->name ?? 'General' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $timetable->location ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $timetable->teacher?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($canManage ?? false)
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('exam-timetables.edit', $timetable->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('exam-timetables.destroy', $timetable->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam timetable?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500">No timetables found for this section.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $timetables->links() }}
        </div>

        <div class="mt-6 text-sm text-gray-600">
            Showing timetables for:
            <span class="font-semibold">{{ $selectedYear?->name ?? 'No section selected' }}</span>
        </div>
    </div>
</div>
@endsection

