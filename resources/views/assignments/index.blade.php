@extends('layouts.dashboard')

@section('title', 'Assignments')

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Assignments</h1>
        <a href="{{ route('assignments.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">New Assignment</a>
    </div>
    <div class="bg-white rounded shadow">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-3">Title</th>
                    <th class="p-3">Class</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Due</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                    <tr class="border-b">
                        <td class="p-3"><a href="{{ route('assignments.show', $a) }}" class="text-blue-600 hover:underline">{{ $a->title }}</a></td>
                        <td class="p-3">{{ $a->schoolClass->name }}</td>
                        <td class="p-3">{{ $a->is_published ? 'Published' : 'Draft' }}</td>
                        <td class="p-3">{{ optional($a->due_date)->format('Y-m-d') }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('assignments.show', $a) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors">View</a>
                                @if(in_array(session('current_role'), ['teacher','admin','super_admin']))
                                    <a href="{{ route('assignments.review', $a) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-purple-600 text-white hover:bg-purple-700 transition-colors">Review</a>
                                    <a href="{{ route('assignments.edit', $a) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors">Edit</a>
                                    @if(!$a->is_published)
                                        <form method="POST" action="{{ route('assignments.publish', $a) }}">
                                            @csrf
                                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors" type="submit">Publish</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('assignments.unpublish', $a) }}">
                                            @csrf
                                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-amber-500 text-white hover:bg-amber-600 transition-colors" type="submit">Unpublish</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('assignments.destroy', $a) }}" onsubmit="return confirm('Delete this assignment? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors" type="submit">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="5">No assignments</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $assignments->links() }}</div>
</div>
@endsection



