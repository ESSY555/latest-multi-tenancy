@extends('layouts.dashboard')

@section('dashboard')
<div class="py-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Attendance Record</h3>
            <p class="text-gray-600">Your attendance summary and history</p>
        </div>

        <!-- Attendance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Present Days</p>
                        <p class="text-2xl font-bold text-green-900">{{ $attendanceSummary['present_days'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Absent Days</p>
                        <p class="text-2xl font-bold text-red-900">{{ $attendanceSummary['absent_days'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">Attendance Rate</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $attendanceSummary['percentage'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Chart Placeholder -->
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-6">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M8 14v20c0 4.418 3.582 8 8 8s8-3.582 8-8V14M8 14c0-4.418 3.582-8 8-8s8 3.582 8 8M8 14c0-1.105.895-2 2-2h2c1.105 0 2 .895 2 2M24 14c0-1.105.895-2 2-2h2c1.105 0 2 .895 2 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Attendance Chart</h3>
            <p class="mt-1 text-sm text-gray-500">Visual attendance tracking will be displayed here.</p>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendance as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->schoolClass->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($item->date)->format('M d, Y') ?? $item->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $ok = strtolower($item->status) === 'present'; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No attendance records yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



