@extends('layouts.dashboard')

@section('title', 'Create Lesson Plan')

@section('dashboard')
@include('lesson-plans.partials.official-format-head')
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Lesson Plan</h1>
                <p class="mt-2 text-gray-600">Type directly in the Bezaleel tables below. You can save as draft and submit later, or submit now for admin review.</p>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <form action="{{ route('lesson-plans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 p-4 sm:p-6">
                    @csrf

                    <div class="official-lesson-print-wrap border border-slate-200 rounded-lg p-3 sm:p-5 overflow-x-auto">
                        @include('lesson-plans.partials.official-format-form', [
                            'mode' => 'create',
                            'subjects' => $subjects,
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
                        <a href="{{ route('lesson-plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">Cancel</a>
                        <button type="submit" name="save_action" value="draft" class="px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-md hover:bg-gray-800 cursor-pointer">Save as Draft</button>
                        <button type="submit" name="save_action" value="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 cursor-pointer">Submit for Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

