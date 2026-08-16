@extends('layouts.dashboard')

@section('title', 'Edit Lesson Plan')

@section('dashboard')
@include('lesson-plans.partials.official-format-head')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Lesson Plan</h1>
                <p class="mt-2 text-gray-600">Edit the plan in the tables below; the saved view uses the same layout.</p>
            </div>
            <a href="{{ route('lesson-plans.show', $lessonPlan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                Back to Report
            </a>
        </div>

        <div class="bg-white shadow sm:rounded-lg p-4 sm:p-6">
            <form action="{{ route('lesson-plans.update', $lessonPlan) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="official-lesson-print-wrap border border-slate-200 rounded-lg p-3 sm:p-5 overflow-x-auto">
                    @include('lesson-plans.partials.official-format-form', [
                        'mode' => 'edit',
                        'lessonPlan' => $lessonPlan,
                        'classes' => $classes,
                    ])
                </div>

                <div class="border-t border-gray-200 pt-6 no-print">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Attachments (optional)</h2>
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Upload images</label>
                    <input type="file" name="attachments[]" id="attachments" multiple accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <p class="mt-1 text-xs text-gray-500">Allowed: JPG, JPEG, PNG, GIF, WEBP. Max 5MB each.</p>
                    @error('attachments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 pt-2 no-print">
                    <a href="{{ route('lesson-plans.show', $lessonPlan) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 cursor-pointer">Save Changes</button>
                </div>
            </form>

            @if($lessonPlan->attachments && $lessonPlan->attachments->count() > 0)
                <div class="mt-8 border-t border-gray-200 pt-6 no-print">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Existing attachments</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($lessonPlan->attachments as $attachment)
                            <div class="relative group bg-gray-50 rounded-md overflow-hidden border">
                                <a href="{{ Storage::disk('public')->url($attachment->path) }}" target="_blank" class="block">
                                    <img src="{{ Storage::disk('public')->url($attachment->path) }}" alt="Attachment" class="w-full h-40 object-cover">
                                </a>
                                @if($lessonPlan->isDraft() && $lessonPlan->teacher_id === auth()->id())
                                    <form action="{{ route('lesson-plans.attachments.destroy', [$lessonPlan, $attachment]) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Remove this attachment?')" class="px-2 py-1 text-xs bg-red-600 text-white rounded cursor-pointer">Remove</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

