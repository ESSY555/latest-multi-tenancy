@extends('layouts.dashboard')

@section('dashboard')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <iframe src="{{ route('academic-calendar.calendar', ['branch' => session('current_branch_id')]) }}"
                    class="w-full"
                    style="min-height: 80vh;"
                    frameborder="0"></iframe>
        </div>
    </div>
</div>
@endsection



