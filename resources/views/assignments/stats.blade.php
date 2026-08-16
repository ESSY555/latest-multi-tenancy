@extends('layouts.dashboard')

@section('title', 'Assignment Stats')

@section('dashboard')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Assignment Statistics</h1>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Assignments</div>
            <div class="text-2xl font-bold">{{ $totals['assignments'] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Published</div>
            <div class="text-2xl font-bold">{{ $totals['published'] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Unpublished</div>
            <div class="text-2xl font-bold">{{ $totals['unpublished'] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Submissions</div>
            <div class="text-2xl font-bold">{{ $totals['submissions'] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">Pending Review</div>
            <div class="text-2xl font-bold">{{ $totals['pending_review'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow">
        <table class="min-w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-3">Class</th>
                    <th class="p-3">Assignments</th>
                    <th class="p-3">Published</th>
                    <th class="p-3">Submissions/Assignment</th>
                    <th class="p-3">Graded</th>
                    <th class="p-3">Pending Review</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byClass as $className => $row)
                    <tr class="border-b">
                        <td class="p-3">{{ $className }}</td>
                        <td class="p-3">{{ $row['assignments'] }}</td>
                        <td class="p-3">{{ $row['published'] }}</td>
                        <td class="p-3">{{ $row['submission_rate'] }}</td>
                        <td class="p-3">{{ $row['graded'] }}</td>
                        <td class="p-3">{{ $row['pending_review'] }}</td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="6">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection



